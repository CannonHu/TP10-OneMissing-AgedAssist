<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Entity_Listing')):

/**
 * Listdom Listing Entity Class.
 *
 * @class LSD_Entity_Listing
 * @version	1.0.0
 */
class LSD_Entity_Listing extends LSD_Entity
{
    public $post;
    public $schema;

    /**
	 * Constructor method
     * @param $post int|object
	 */
	public function __construct($post = NULL)
    {
        // Call Parent Constructor
        parent::__construct();

        // Get the Post
        $this->post = get_post($post);
	}

	public function save(Array $data = array(), $trigger_actions = true)
    {
        $category = isset($data['listing_category']) ? sanitize_text_field($data['listing_category']) : NULL;
        $term = $category ? get_term_by('term_id', $category, LSD_Base::TAX_CATEGORY) : NULL;

        // Listdom DB
        $db = new LSD_db();

        // Category is valid
        if(trim($category) and !empty($term) and !is_wp_error($term))
        {
            // Category Term
            wp_set_object_terms($this->post->ID, $term->term_id, LSD_Base::TAX_CATEGORY, true);

            // Primary Category Meta
            update_post_meta($this->post->ID, 'lsd_primary_category', $term->term_id);
        }
        // A valid category is required!
        elseif($trigger_actions)
        {
            LSD_Flash::add(esc_html__("A valid Listing Category is required.", 'listdom'), 'error');
        }

        update_post_meta($this->post->ID, 'lsd_object_type', (isset($data['object_type']) ? sanitize_text_field($data['object_type']) : 'marker'));
        update_post_meta($this->post->ID, 'lsd_zoomlevel', (isset($data['zoomlevel']) ? sanitize_text_field($data['zoomlevel']) : $this->settings['map_backend_zl']));

        // Lat / Long
        $lat = (isset($data['latitude']) ? sanitize_text_field($data['latitude']) : $this->settings['map_backend_lt']);
        $lng = (isset($data['longitude']) ? sanitize_text_field($data['longitude']) : $this->settings['map_backend_ln']);

        // Geo Point on WP Meta Table
        update_post_meta($this->post->ID, 'lsd_address', (isset($data['address']) ? sanitize_text_field($data['address']) : ''));
        update_post_meta($this->post->ID, 'lsd_latitude', $lat);
        update_post_meta($this->post->ID, 'lsd_longitude', $lng);

        // Listdom Data ID
        $data_id = $db->select("SELECT `id` FROM `#__lsd_data` WHERE `id`='".esc_sql($this->post->ID)."'");

        // Insert Geo Point on Listdom Table
        if(!$data_id) $db->q("INSERT INTO `#__lsd_data` (`id`, `latitude`, `longitude`) VALUES ('".esc_sql($this->post->ID)."', '".$lat."', '".$lng."')");
        else $db->q("UPDATE `#__lsd_data` SET `latitude`='".$lat."', `longitude`='".$lng."' WHERE `id`='".esc_sql($this->post->ID)."'");

        // Update Point Field
        $db->q("UPDATE `#__lsd_data` SET `point`=Point(`latitude`, `longitude`)");

        // Shape
        update_post_meta($this->post->ID, 'lsd_shape_type', (isset($data['shape_type']) ? sanitize_text_field($data['shape_type']) : ''));
        update_post_meta($this->post->ID, 'lsd_shape_paths', (isset($data['shape_paths']) ? sanitize_text_field($data['shape_paths']) : ''));
        update_post_meta($this->post->ID, 'lsd_shape_radius', (isset($data['shape_radius']) ? sanitize_text_field($data['shape_radius']) : ''));

        // Attributes
        $attributes = isset($data['attributes']) ? $data['attributes'] : array();
        update_post_meta($this->post->ID, 'lsd_attributes', $attributes);

        // Save attributes one by one
        foreach($attributes as $key=>$attribute)
        {
            update_post_meta($this->post->ID, 'lsd_attribute_'.$key, sanitize_text_field($attribute));
        }

        // Listing Link
        update_post_meta($this->post->ID, 'lsd_link', ((isset($data['link']) and filter_var($data['link'], FILTER_VALIDATE_URL)) ? esc_url_raw($data['link']) : ''));

        // Price Options
        update_post_meta($this->post->ID, 'lsd_price', (isset($data['price']) ? sanitize_text_field($data['price']) : 0));
        update_post_meta($this->post->ID, 'lsd_price_max', (isset($data['price_max']) ? sanitize_text_field($data['price_max']) : 0));
        update_post_meta($this->post->ID, 'lsd_price_after', (isset($data['price_after']) ? sanitize_text_field($data['price_after']) : ''));
        update_post_meta($this->post->ID, 'lsd_price_class', (isset($data['price_class']) ? sanitize_text_field($data['price_class']) : 2));
        update_post_meta($this->post->ID, 'lsd_currency', (isset($data['currency']) ? sanitize_text_field($data['currency']) : 'USD'));

        // Availability
        update_post_meta($this->post->ID, 'lsd_ava', (isset($data['ava']) ? $data['ava'] : array()));

        // Contact Details
        update_post_meta($this->post->ID, 'lsd_email', (isset($data['email']) ? sanitize_email($data['email']) : ''));
        update_post_meta($this->post->ID, 'lsd_phone', (isset($data['phone']) ? sanitize_text_field($data['phone']) : ''));
        update_post_meta($this->post->ID, 'lsd_website', (isset($data['website']) ? esc_url($data['website']) : ''));

        // Remark
        update_post_meta($this->post->ID, 'lsd_remark', (isset($data['remark']) ? $data['remark'] : ''));

        // Display Options
        update_post_meta($this->post->ID, 'lsd_displ', (isset($data['displ']) ? $data['displ'] : array()));

        // Gallery
        update_post_meta($this->post->ID, 'lsd_gallery', (isset($data['gallery']) ? array_map('sanitize_text_field', $data['gallery']) : array()));

        // Embeds
        update_post_meta($this->post->ID, 'lsd_embeds', ((isset($data['embeds']) and is_array($data['embeds'])) ? $this->indexify($data['embeds']) : array()));

        // Guest Data
        if(isset($data['guest_email']))
        {
            // Guest Email
            $guest_email = (isset($data['guest_email']) ? sanitize_email($data['guest_email']) : '');

            update_post_meta($this->post->ID, 'lsd_guest_email', $guest_email);
            update_post_meta($this->post->ID, 'lsd_guest_message', (isset($data['guest_message']) ? sanitize_text_field($data['guest_message']) : ''));
        }

        // Create user and assign listing to new user
        $guest_email = get_post_meta($this->post->ID, 'lsd_guest_email', true);
        if(is_email($guest_email) and get_post_status($this->post->ID) === 'publish')
        {
            $user_id = NULL;
            $exists = email_exists($guest_email);

            if($exists) $user_id = $exists;
            else
            {
                $registered = register_new_user($guest_email, $guest_email);
                if(!is_wp_error($registered)) $user_id = $registered;
            }

            // Assign Listing to new Owner
            LSD_Main::assign($this->post->ID, $user_id);
        }

        // New Listing Action
        if($trigger_actions and !$data_id) do_action('lsd_new_listing', $this->post->ID);
    }

    public function get_title()
    {
        $element = new LSD_Element_Title();
        return $element->get($this->post->ID);
    }

    public function get_content($content)
    {
        $element = new LSD_Element_Content();
        return $element->get($content);
    }

    public function get_remark()
    {
        $element = new LSD_Element_Remark();
        return $element->get($this->post->ID);
    }

    public function get_excerpt($limit = 15, $read_more = false)
    {
        $element = new LSD_Element_Content();
        return $element->excerpt($this->post->ID, $limit, $read_more);
    }

    public function get_features($method = 'list', $show_icons = true)
    {
        $element = new LSD_Element_Features($show_icons);

        if($method == 'text') return $element->text($this->post->ID, '/');
        else return $element->get($this->post->ID);
    }

    public function get_tags()
    {
        $element = new LSD_Element_Tags();
        return $element->get($this->post->ID);
    }

    public function get_categories($show_color = true, $multiple_categories = false, $color_method = 'bg')
    {
        $element = new LSD_Element_Categories($show_color, $multiple_categories, $color_method);
        return $element->get($this->post->ID);
    }

    public function get_attributes($show_icons = false)
    {
        $element = new LSD_Element_Attributes();
        return $element->get($this->post->ID, $show_icons);
    }

    public function get_map($args = array())
    {
        $element = new LSD_Element_Map();
        return $element->get($this->post->ID, $args);
    }

    public function get_featured_image($size = 'full')
    {
        $element = new LSD_Element_Image();
        return $element->get($size, $this->post->ID);
    }

    public function get_cover_image($size = array(390, 260))
    {
        $element = new LSD_Element_Image();
        return $element->cover($size, $this->post->ID);
    }

    public function get_gallery($params = array())
    {
        $element = new LSD_Element_Gallery();
        return $element->get($params, $this->post->ID);
    }

    public function get_embeds()
    {
        $element = new LSD_Element_Embed();
        return $element->get($this->post->ID);
    }

    public function get_address($icon = true)
    {
        $element = new LSD_Element_Address();
        return $element->get($this->post->ID, $icon);
    }

    public function get_locations()
    {
        $element = new LSD_Element_Locations();
        return $element->get($this->post->ID);
    }

    public function get_price($minimized = false)
    {
        $element = new LSD_Element_Price();
        return $element->get($this->post->ID, $minimized);
    }

    public function get_price_class()
    {
        $class = (int) get_post_meta($this->post->ID, 'lsd_price_class', true);
        if(!trim($class)) $class = 2;

        switch($class)
        {
            case 1:

                $tag = '$';
                $label = esc_html__('Cheap', 'listdom');
                break;

            case 3:

                $tag = '$$$';
                $label = esc_html__('High', 'listdom');
                break;

            case 4:

                $tag = '$$$$';
                $label = esc_html__('Ultra High', 'listdom');
                break;

            default:

                $tag = '$$';
                $label = esc_html__('Normal', 'listdom');
                break;
        }

        return '<span title="'.esc_attr($label).'">'.$tag.'</span>';
    }

    public function get_availability($oneday = false, $day = NULL)
    {
        $element = new LSD_Element_Availability($oneday, $day);
        return $element->get($this->post->ID);
    }

    public function get_phone()
    {
        $phone = get_post_meta($this->post->ID, 'lsd_phone', true);
        if(trim($phone) == '') return '';

        return '<i class="lsd-icon fas fa-phone-square-alt" aria-hidden="true"></i> '.esc_html($phone);
    }

    public function get_email()
    {
        $email = get_post_meta($this->post->ID, 'lsd_email', true);
        if(trim($email) == '') return '';

        return '<i class="lsd-icon fa fa-envelope" aria-hidden="true"></i> '.esc_html($email);
    }

    public function get_owner($layout = 'details', $args = array())
    {
        $element = new LSD_Element_Owner($layout, $args);
        return $element->get($this->post->ID);
    }

    public function get_abuse()
    {
        $element = new LSD_Element_Abuse();
        return $element->get($this->post->ID);
    }

    public function get_labels()
    {
        $element = new LSD_Element_Labels();
        return $element->get($this->post->ID);
    }

    public function get_share_buttons($layout = 'archive', $args = array())
    {
        $element = new LSD_Element_Share($layout, $args);
        return $element->get($this->post->ID);
    }

    public function get_marker()
    {
        $category = $this->get_data_category();
        $icon = isset($category->term_id) ? LSD_Taxonomies::icon($category->term_id) : '';
        $bgcolor = isset($category->term_id) ? get_term_meta($category->term_id, 'lsd_color', true) : '';

        $marker = '<div class="lsd-marker-container" style="background-color: '.esc_attr($bgcolor).'">
            '.$icon.'
        </div>';

        // Apply Filters
        return LSD_Kses::element(apply_filters('lsd_marker', $marker, $this));
    }

    public function get_infowindow()
    {
        $element = new LSD_Element_Infowindow();
        $element->set_listing($this);

        $infowindow = $element->get($this->post->ID);

        // Apply Filters
        return apply_filters('lsd_infowindow', $infowindow, $element);
    }

    /**
     * @return bool|WP_Term
     */
    public function get_data_category()
    {
        return self::get_primary_category($this->post->ID);
    }

    /**
     * @param $listing_id
     * @return bool|WP_Term
     */
    public static function get_primary_category($listing_id)
    {
        // Primary Category
        $primary_id = get_post_meta($listing_id, 'lsd_primary_category', true);
        if($primary_id)
        {
            $term = get_term($primary_id);
            if($term instanceof WP_Term) return $term;
        }

        // Get the First Category
        $terms = wp_get_post_terms($listing_id, LSD_Base::TAX_CATEGORY, array());
        if(!count($terms)) return false;

        return $terms[0];
    }

    public function get_category_color()
    {
        $category = $this->get_data_category();
        return get_term_meta($category->term_id, 'lsd_color', true);
    }

    public function get_contact_info()
    {
        $element = new LSD_Element_Contact();
        $element->set_listing($this);

        return $element->get($this->post->ID);
    }

    public function get_favorite_button($type = 'heart')
    {
        return apply_filters('lsd_favorite_button', '', $type, $this);
    }

    public function is_claimed()
    {
        return $this->is('claimed');
    }

    public function get_claim_button()
    {
        return apply_filters('lsd_claim_button', '', $this);
    }

    public function get_rate_stars($type = 'stars', $link = true)
    {
        return apply_filters('lsd_rate_stars', '', $type, $link, $this);
    }

    public function get_shape_fill_color()
    {
        return isset($this->settings['map_shape_fill_color']) ? $this->settings['map_shape_fill_color'] : '#1e90ff';
    }

    public function get_shape_fill_opacity()
    {
        return isset($this->settings['map_shape_fill_opacity']) ? $this->settings['map_shape_fill_opacity'] : '0.3';
    }

    public function get_shape_stroke_color()
    {
        return isset($this->settings['map_shape_stroke_color']) ? $this->settings['map_shape_stroke_color'] : '#1e74c7';
    }

    public function get_shape_stroke_opacity()
    {
        return isset($this->settings['map_shape_stroke_opacity']) ? $this->settings['map_shape_stroke_opacity'] : '0.8';
    }

    public function get_shape_stroke_weight()
    {
        return isset($this->settings['map_shape_stroke_weight']) ? $this->settings['map_shape_stroke_weight'] : '2';
    }

    public function image_class_wrapper()
    {
        return (has_post_thumbnail($this->post->ID) ? 'lsd-has-image' : 'lsd-has-no-image');
    }

    public function get_meta($key)
    {
        return get_post_meta($this->post->ID, $key, true);
    }

    public function is_shape()
    {
        return $this->get_meta('lsd_object_type') === 'shape';
    }

    public function update_visits()
    {
        $visits = $this->get_visits();
        update_post_meta($this->post->ID, 'lsd_visits', ++$visits);
    }

    public function get_visits()
    {
        $visits = $this->get_meta('lsd_visits');
        if(!$visits) $visits = apply_filters('lsd_listing_visits_start', 0);

        return $visits;
    }

    public function update_contacts()
    {
        $visits = $this->get_contacts();
        update_post_meta($this->post->ID, 'lsd_contacts', ++$visits);
    }

    public function get_contacts()
    {
        $contacts = $this->get_meta('lsd_contacts');
        if(!$contacts) $contacts = apply_filters('lsd_listing_contacts_start', 0);

        return $contacts;
    }

    public function is($key = NULL)
    {
        // Status
        $status = false;

        // No Key
        if(!trim($key)) return $status;

        switch($key)
        {
            case 'claimed':

                $status = (boolean) get_post_meta($this->post->ID, 'lsd_claimed', true);
                break;
        }

        return $status;
    }

    public function get_children($limit = -1)
    {
        // Get Childs
        return get_posts(array(
            'post_type' => LSD_Base::PTYPE_LISTING,
            'posts_per_page' => $limit,
            'meta_query' => array(array
            (
                'key' => 'lsd_parent',
                'value' => $this->post->ID
            ))
        ));
    }

    public function has_child()
    {
        return (boolean) count($this->get_children(1));
    }
}

endif;