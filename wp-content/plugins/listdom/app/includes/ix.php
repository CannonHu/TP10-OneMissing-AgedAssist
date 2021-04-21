<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_IX')):

/**
 * Listdom Import / Export class.
 */
class LSD_IX extends LSD_Base
{
    protected $db;

    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();

        // DB Library
        $this->db = new LSD_db();
    }

    protected function data()
    {
        $listings = get_posts(array(
            'post_type'=>LSD_Base::PTYPE_LISTING,
            'post_status'=>array('publish', 'pending', 'draft', 'future', 'private'),
            'posts_per_page'=>'-1',
        ));

        $data = array();
        foreach($listings as $listing)
        {
            // Force to Array
            $listing = (array) $listing;

            // ID
            $listing_id = $listing['ID'];

            // Remove Useless Keys
            foreach(array(
                'ID', 'comment_count', 'comment_status', 'filter', 'guid',
                'menu_order', 'ping_status', 'pinged', 'to_ping', 'post_content_filtered',
                'post_parent', 'post_mime_type'
            ) as $key) unset($listing[$key]);

            $metas = $this->get_post_meta($listing_id);

            // Remove Useless Keys
            foreach($metas as $key => $value)
            {
                if(in_array($key, array(
                    '_edit_last', '_edit_lock', '_thumbnail_id',
                    'lsd_attributes', 'lsd_gallery',
                )) or strpos($key, 'lsd_attribute_') !== false) unset($metas[$key]);
            }

            // Meta Values
            $listing['meta'] = $metas;

            // Taxonomies
            $listing['taxonomies'] = $this->get_taxonomies($listing_id);

            // Gallery
            $listing['gallery'] = $this->get_gallery($listing_id);

            // Featured Image
            $listing['image'] = get_the_post_thumbnail_url($listing_id, 'full');

            // Attributes
            $listing['attributes'] = $this->get_attributes($listing_id);

            $data[] = $listing;
        }

        return $data;
    }

    public function import($file)
    {
        // Unlimited Time Needed!
        set_time_limit(0);

        // Content to Import
        $content = LSD_File::read($file);

        $ex = explode('.', $file);
        $extension = strtolower(end($ex));

        switch($extension)
        {
            case 'json':

                return $this->import_json($content);
                break;

            default:
                return false;
        }
    }

    public function import_json($JSON)
    {
        $listings = json_decode($JSON, true);
        return $this->collection($listings);
    }

    public function collection($listings)
    {
        $ids = array();
        foreach($listings as $listing)
        {
            $ids[] = $this->save($listing);
        }

        return $ids;
    }

    public function save($listing)
    {
        $post = array(
            'post_title' => $listing['post_title'],
            'post_content' => (isset($listing['post_content']) ? $listing['post_content'] : ''),
            'post_type' => LSD_Base::PTYPE_LISTING,
            'post_status' => (isset($listing['post_status']) ? $listing['post_status'] : 'publish'),
            'post_date' => (isset($listing['post_date']) ? date('Y-m-d', strtotime($listing['post_date'])) : NULL),
            'post_password' => (isset($listing['post_password']) ? $listing['post_password'] : ''),
        );

        // Don't Duplicate the Listing by Unique ID
        if(isset($listing['unique_id']) and trim($listing['unique_id']))
        {
            $db = new LSD_db();

            $exists = $db->select("SELECT `post_id` FROM `#__postmeta` WHERE `meta_value`='".esc_sql($listing['unique_id'])."' AND `meta_key`='lsd_sys_unique_id'", 'loadResult');
            if($exists) $post['ID'] = $exists;
        }
        // Don't Duplicate the Listing by Title and Content
        else
        {
            $exists = post_exists($listing['post_title'], (isset($listing['post_content']) ? $listing['post_content'] : ''), '', LSD_Base::PTYPE_LISTING);
            if($exists) $post['ID'] = $exists;
        }

        // Insert User
        if(isset($listing['post_author']) and trim($listing['post_author']) and is_email($listing['post_author']))
        {
            $email = sanitize_email($listing['post_author']);

            $exists = email_exists($email);
            if($exists) $post['post_author'] = $exists;
            else
            {
                $user_id = register_new_user($email, $email);
                if(!is_wp_error($user_id)) $post['post_author'] = $user_id;
            }
        }

        // Insert / Update Post
        $post_id = wp_insert_post($post);

        // Import Taxonomies
        $taxonomies = (isset($listing['taxonomies']) and is_array($listing['taxonomies'])) ? $listing['taxonomies'] : array();
        foreach($taxonomies as $taxonomy => $terms)
        {
            $t = array();
            foreach($terms as $term)
            {
                $exists = term_exists($term['name'], $taxonomy);

                if(is_array($exists) and isset($exists['term_id'])) $term_id = (int) $exists['term_id'];
                else
                {
                    // Create Term
                    $wpt = wp_insert_term($term['name'], $taxonomy, array(
                        'description' => (isset($term['description']) ? $term['description'] : ''),
                        'parent' => (isset($term['parent']) ? $term['parent'] : 0),
                        'slug' => (isset($term['slug']) ? $term['slug'] : '')
                    ));

                    // An Error Occurred
                    if(!is_array($wpt)) continue;

                    // Term ID
                    $term_id = (int) $wpt['term_id'];

                    // Import Term Meta
                    if(isset($term['meta']) and is_array($term['meta']) and count($term['meta']))
                    {
                        foreach($term['meta'] as $key => $value) update_term_meta($term_id, $key, $value);
                    }

                    // Import Image
                    if(isset($term['image']) and trim($term['image']))
                    {
                        $attachment_id = $this->attach($term['image']);
                        if($attachment_id) update_term_meta($term_id, 'lsd_image', $attachment_id);
                    }
                }

                $t[] = $term_id;
            }

            wp_set_post_terms($post_id, $t, $taxonomy);
        }

        // Import Image
        if(isset($listing['image']) and trim($listing['image']))
        {
            $attachment_id = $this->attach(trim($listing['image']));
            if($attachment_id) set_post_thumbnail($post_id, $attachment_id);
        }

        // Import Gallery
        $gallery = array();
        if(isset($listing['gallery']) and is_array($listing['gallery']) and count($listing['gallery']))
        {
            foreach($listing['gallery'] as $image)
            {
                $attachment_id = $this->attach(trim($image));
                if($attachment_id) $gallery[] = $attachment_id;
            }
        }

        // Import Attributes
        $attributes = array();
        if(isset($listing['attributes']) and is_array($listing['attributes']) and count($listing['attributes']))
        {
            foreach($listing['attributes'] as $attribute)
            {
                $term = isset($attribute['term']) ? $attribute['term'] : array();
                if(!is_array($term) or (is_array($term) and !count($term))) continue;

                $exists = term_exists($term['name'], LSD_Base::TAX_ATTRIBUTE);

                if(is_array($exists) and isset($exists['term_id'])) $term_id = (int) $exists['term_id'];
                else
                {
                    // Create Term
                    $wpt = wp_insert_term($term['name'], LSD_Base::TAX_ATTRIBUTE, array(
                        'description' => (isset($term['description']) ? $term['description'] : ''),
                        'parent' => (isset($term['parent']) ? $term['parent'] : 0),
                        'slug' => (isset($term['slug']) ? $term['slug'] : '')
                    ));

                    // An Error Occurred
                    if(!is_array($wpt)) continue;

                    // Term ID
                    $term_id = (int) $wpt['term_id'];

                    // Import Term Meta
                    if(isset($term['meta']) and is_array($term['meta']) and count($term['meta']))
                    {
                        foreach($term['meta'] as $key => $value) update_term_meta($term_id, $key, $value);
                    }
                }

                $value = isset($attribute['value']) ? $attribute['value'] : '';

                // Add to Attributes
                $attributes[$term_id] = $value;
            }
        }

        // Metas
        $metas = (isset($listing['meta']) and is_array($listing['meta'])) ? $listing['meta'] : array();

        // Prepare Data
        $data = array(
            'listing_category' => NULL,
            'object_type' => (isset($metas['lsd_object_type']) ? $metas['lsd_object_type'] : 'marker'),
            'zoomlevel' => (isset($metas['lsd_zoomlevel']) ? $metas['lsd_zoomlevel'] : 6),
            'latitude' => (isset($metas['lsd_latitude']) ? $metas['lsd_latitude'] : NULL),
            'longitude' => (isset($metas['lsd_longitude']) ? $metas['lsd_longitude'] : NULL),
            'address' => (isset($metas['lsd_address']) ? $metas['lsd_address'] : ''),
            'shape_type' => (isset($metas['lsd_shape_type']) ? $metas['lsd_shape_type'] : ''),
            'shape_paths' => (isset($metas['lsd_shape_paths']) ? $metas['lsd_shape_paths'] : ''),
            'shape_radius' => (isset($metas['lsd_shape_radius']) ? $metas['lsd_shape_radius'] : ''),
            'attributes' => $attributes,
            'link' => (isset($metas['lsd_link']) ? $metas['lsd_link'] : ''),
            'price' => (isset($metas['lsd_price']) ? $metas['lsd_price'] : 0),
            'price_max' => (isset($metas['lsd_price_max']) ? $metas['lsd_price_max'] : 0),
            'price_after' => (isset($metas['lsd_price_after']) ? $metas['lsd_price_after'] : ''),
            'currency' => (isset($metas['lsd_currency']) ? $metas['lsd_currency'] : 'USD'),
            'ava' => (isset($metas['lsd_ava']) ? $metas['lsd_ava'] : array()),
            'email' => (isset($metas['lsd_email']) ? $metas['lsd_email'] : ''),
            'phone' => (isset($metas['lsd_phone']) ? $metas['lsd_phone'] : ''),
            'website' => (isset($metas['lsd_website']) ? $metas['lsd_website'] : ''),
            'remark' => (isset($metas['lsd_remark']) ? $metas['lsd_remark'] : ''),
            'displ' => (isset($metas['lsd_displ']) ? $metas['lsd_displ'] : array()),
            'gallery' => $gallery,
        );

        if(isset($metas['lsd_guest_email']))
        {
            $data['guest_email'] = (isset($metas['lsd_guest_email']) ? sanitize_email($metas['lsd_guest_email']) : '');
            $data['guest_message'] = (isset($metas['lsd_guest_message']) ? $metas['lsd_guest_message'] : '');
        }

        $entity = new LSD_Entity_Listing($post_id);
        $entity->save($data, false);

        // Save the Unique ID
        if(isset($listing['unique_id']) and trim($listing['unique_id'])) update_post_meta($post_id, 'lsd_sys_unique_id', $listing['unique_id']);

        // New Listing Imported
        do_action('lsd_listing_imported', $post_id, $listing);

        return $post_id;
    }

    public function get_attributes($post_id)
    {
        $attributes = array();

        $values = get_post_meta($post_id, 'lsd_attributes', true);
        if(!is_array($values)) $values = array();

        foreach($values as $id => $value)
        {
            $term = (array) get_term($id);

            // Remove Useless Keys
            foreach(array(
                'count', 'filter', 'term_group',
                'term_id', 'term_taxonomy_id',
            ) as $key) unset($term[$key]);

            // Meta Values
            $term['meta'] = $this->get_term_meta($id);

            $attr = array(
                'value' => $value,
                'term' => $term,
            );

            $attributes[] = $attr;
        }

        return $attributes;
    }

    public function get_taxonomies($post_id)
    {
        $taxonomies = array();

        foreach(array(
            LSD_Base::TAX_CATEGORY,
            LSD_Base::TAX_LABEL,
            LSD_Base::TAX_LOCATION,
            LSD_Base::TAX_FEATURE,
            LSD_Base::TAX_TAG,
        ) as $taxonomy)
        {
            $terms = get_the_terms($post_id, $taxonomy);
            if($terms and !is_wp_error($terms))
            {
                $t = array();
                foreach($terms as $term)
                {
                    // Force to Array
                    $term = (array) $term;

                    // Term ID
                    $term_id = $term['term_id'];

                    // Remove Useless Keys
                    foreach(array(
                        'count', 'filter',
                        'term_group', 'term_taxonomy_id',
                    ) as $key) unset($term[$key]);

                    // Metas
                    $metas = $this->get_term_meta($term_id);

                    // Image
                    if(isset($metas['lsd_image']))
                    {
                        $term['image'] = wp_get_attachment_url($metas['lsd_image']);
                        unset($metas['lsd_image']);
                    }

                    $term['meta'] = $metas;

                    $t[] = $term;
                }

                $taxonomies[$taxonomy] = $t;
            }
        }

        return $taxonomies;
    }

    public function get_gallery($post_id)
    {
        $value = get_post_meta($post_id, 'lsd_gallery', true);
        if(!is_array($value)) $value = array();

        $gallery = array();
        foreach($value as $attachment_id)
        {
            $image = wp_get_attachment_url($attachment_id);
            if(!$image) continue;

            $gallery[] = $image;
        }

        return $gallery;
    }

    public function attach($image)
    {
        $buffer = LSD_File::download($image);
        if(!$buffer) return false;

        $upload = wp_upload_bits(basename($image), NULL, $buffer);

        $file = $upload['file'];
        $wp_filetype = wp_check_filetype($file, NULL);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($file),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attachment_id = wp_insert_attachment($attachment, $file);
        $attach_data = wp_generate_attachment_metadata($attachment_id, $file);
        wp_update_attachment_metadata($attachment_id, $attach_data);

        return $attachment_id;
    }
}

endif;