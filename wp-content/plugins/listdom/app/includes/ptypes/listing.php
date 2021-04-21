<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_PTypes_Listing')):

/**
 * Listdom Listing Post Types Class.
 *
 * @class LSD_PTypes_Listing
 * @version	1.0.0
 */
class LSD_PTypes_Listing extends LSD_PTypes
{
    public $PT;
    protected $settings;
    protected $details_page_options;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        $this->PT = LSD_Base::PTYPE_LISTING;

        // Listdom Settings
        $this->settings = LSD_Options::settings();

        // Details Page options
        $this->details_page_options = LSD_Options::details_page();
	}
    
    public function init()
    {
        add_action('init', array($this, 'register_post_type'));

        add_filter('manage_'.$this->PT.'_posts_columns', array($this, 'filter_columns'));
        add_action('manage_'.$this->PT.'_posts_custom_column', array($this, 'filter_columns_content'), 10, 2);

        // Search Options
        add_action('restrict_manage_posts', array($this, 'add_filters'));
        add_filter('posts_join', array($this, 'filters_join'));
        add_filter('posts_where', array($this, 'filters_where'));

        add_action('add_meta_boxes', array($this, 'register_metaboxes'), 10, 2);
        add_action('save_post', array($this, 'save'), 10, 2);
        add_filter('post_type_link', array($this, 'filter_link'), 10, 2);

        // Delete
        add_action('delete_post', array($this, 'delete'));

        // New Status
        add_action('transition_post_status', array($this, 'status'), 10, 3);

        $single = new LSD_PTypes_Listing_Single();
        add_filter('the_content', array($single, 'filter_content'));
        add_filter('lsd_ptype_listing_supports', array($single, 'add_comments_support'));
        add_filter('template_include', array($single, 'template'));

        $archive = new LSD_PTypes_Listing_Archive();
        add_action('lsd_listings', array($archive, 'do_listings'), 10, 3);
    }
    
    public function register_post_type()
    {
        $supports = apply_filters('lsd_ptype_listing_supports', array('title', 'editor', 'thumbnail', 'author'));

        $args = array(
            'labels'=>array
            (
                'name'=>esc_html__('Listings', 'listdom'),
                'singular_name'=>esc_html__('Listing', 'listdom'),
                'add_new'=>esc_html__('Add Listing', 'listdom'),
                'add_new_item'=>esc_html__('Add New Listing', 'listdom'),
                'edit_item'=>esc_html__('Edit Listing', 'listdom'),
                'new_item'=>esc_html__('New Listing', 'listdom'),
                'view_item'=>esc_html__('View Listing', 'listdom'),
                'view_items'=>esc_html__('View Listings', 'listdom'),
                'search_items'=>esc_html__('Search Listings', 'listdom'),
                'not_found'=>esc_html__('No listings found!', 'listdom'),
                'not_found_in_trash'=>esc_html__('No listings found in Trash!', 'listdom'),
                'all_items'=>esc_html__('All Listings', 'listdom'),
                'archives'=>esc_html__('Listing Archives', 'listdom'),
            ),
            'public'=>true,
            'show_ui'=>true,
            'show_in_menu'=>true,
            'show_in_rest'=>false,
            'supports'=>$supports,
            'rewrite'=>array
            (
                'slug'=>LSD_Options::slug(),
                'ep_mask'=>LSD_Base::EP_LISTING
            ),
            'menu_icon'=>'dashicons-media-text',
            'menu_position'=>27,
        );

        register_post_type($this->PT, apply_filters('lsd_ptype_listing_args', $args));
    }

    public function filter_columns($columns)
    {
        // Move the date column to the end
        $date = $columns['date'];
        $author = $columns['author'];

        unset($columns['date']);
        unset($columns['author']);

        $columns['address'] = esc_html__('Address', 'listdom');
        $columns['category'] = esc_html__('Category', 'listdom');
        $columns['author'] = $author;
        $columns['date'] = $date;

        return $columns;
    }

    public function filter_columns_content($column_name, $post_id)
    {
        if($column_name == 'category')
        {
            $terms = wp_get_object_terms($post_id, LSD_Base::TAX_CATEGORY);
            echo (isset($terms[0]) and isset($terms[0]->name)) ? '<strong>'.esc_html($terms[0]->name).'</strong>' : '';
        }
        elseif($column_name == 'address')
        {
            echo esc_html(get_post_meta($post_id, 'lsd_address', true));
        }
    }
    
    public function register_metaboxes()
    {
        add_meta_box('lsd_metabox_address', esc_html__('Location', 'listdom'), array($this, 'metabox_address'), $this->PT, 'normal', 'high');
        add_meta_box('lsd_metabox_details', esc_html__('Details', 'listdom'), array($this, 'metabox_details'), $this->PT, 'normal', 'high');

        // Register Metaboxes
        do_action('lsd_register_metaboxes');
    }

    public function metabox_address($post)
    {
        // Generate output
        include $this->include_html_file('metaboxes/listing/address.php', array('return_path'=>true));
    }

    public function metabox_details($post)
    {
        // Generate output
        include $this->include_html_file('metaboxes/listing/details.php', array('return_path'=>true));
    }

    public function save($post_id, $post)
    {
        // It's not a listing
        if($post->post_type !== $this->PT) return;

        // Nonce is not set!
        if(!isset($_POST['_lsdnonce'])) return;

        // Nonce is not valid!
        if(!wp_verify_nonce(sanitize_text_field($_POST['_lsdnonce']), 'lsd_listing_cpt')) return;

        // We don't need to do anything on post auto save
        if(defined('DOING_AUTOSAVE') and DOING_AUTOSAVE) return;

        // Get Listdom Data
        $lsd = isset($_POST['lsd']) ? $_POST['lsd'] : array();

        // Gallery
        if(isset($lsd['_gallery']) and is_array($lsd['_gallery']))
        {
            $lsd['gallery'] = $lsd['_gallery'];
            unset($lsd['_gallery']);
        }

        // Embeds
        if(isset($lsd['_embeds']) and is_array($lsd['_embeds']))
        {
            $lsd['embeds'] = $lsd['_embeds'];
            unset($lsd['_embeds']);
        }

        // Sanitization
        array_walk_recursive($lsd, 'sanitize_text_field');

        // Save the Data
        $entity = new LSD_Entity_Listing($post_id);
        $entity->save($lsd, true);
    }

    public function delete($post_id)
    {
        // Post
        $post = get_post($post_id);

        // It's not a listing
        if($post->post_type != LSD_Base::PTYPE_LISTING) return false;

        $db = new LSD_db();
        return $db->q("DELETE FROM `#__lsd_data` WHERE `id`=".esc_sql($post_id), 'DELETE');
    }

    public function status($new_status, $old_status, $post)
    {
        if($post->post_type === $this->PT)
        {
            do_action('lsd_listing_status_changed', $post->ID, $old_status, $new_status);
        }
    }

    public function filter_link($url, $post)
    {
        if(LSD_Base::PTYPE_LISTING == get_post_type($post))
        {
            $link = get_post_meta($post->ID, 'lsd_link', true);
            return (trim($link) and filter_var($link, FILTER_VALIDATE_URL)) ? $link : $url;
        }

        return $url;
    }

    public function add_filters($post_type)
    {
        if($post_type != $this->PT) return;

        $taxonomy = LSD_Base::TAX_CATEGORY;
        if(wp_count_terms($taxonomy)) wp_dropdown_categories(array(
            'show_option_all'=>esc_html__('Show all categories', 'listdom'),
            'taxonomy'=>$taxonomy,
            'name'=>$taxonomy,
            'value_field'=>'slug',
            'orderby'=>'name',
            'order'=>'ASC',
            'selected'=>(isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : ''),
            'show_count'=>false,
            'hide_empty'=>false,
            'hierarchical'=>1,
        ));

        $taxonomy = LSD_Base::TAX_LOCATION;
        if(wp_count_terms($taxonomy)) wp_dropdown_categories(array(
            'show_option_all'=>esc_html__('Show all locations', 'listdom'),
            'taxonomy'=>$taxonomy,
            'name'=>$taxonomy,
            'value_field'=>'slug',
            'orderby'=>'name',
            'order'=>'ASC',
            'selected'=>(isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : ''),
            'show_count'=>false,
            'hide_empty'=>false,
        ));

        $taxonomy = LSD_Base::TAX_FEATURE;
        if(wp_count_terms($taxonomy)) wp_dropdown_categories(array(
            'show_option_all'=>esc_html__('Show all features', 'listdom'),
            'taxonomy'=>$taxonomy,
            'name'=>$taxonomy,
            'value_field'=>'slug',
            'orderby'=>'name',
            'order'=>'ASC',
            'selected'=>(isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : ''),
            'show_count'=>false,
            'hide_empty'=>false,
        ));

        $taxonomy = LSD_Base::TAX_LABEL;
        if(wp_count_terms($taxonomy)) wp_dropdown_categories(array(
            'show_option_all'=>esc_html__('Show all labels', 'listdom'),
            'taxonomy'=>$taxonomy,
            'name'=>$taxonomy,
            'value_field'=>'slug',
            'orderby'=>'name',
            'order'=>'ASC',
            'selected'=>(isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : ''),
            'show_count'=>false,
            'hide_empty'=>false,
        ));
    }

    public function filters_join($join)
    {
        global $pagenow, $wpdb;
        if(is_admin() && $pagenow == 'edit.php' && !empty($_GET['post_type']) && $_GET['post_type'] == $this->PT && !empty($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_EMAIL))
        {
            $join .= 'LEFT JOIN '.$wpdb->postmeta.' ON '.$wpdb->posts.'.ID = '.$wpdb->postmeta.'.post_id ';
        }

        return $join;
    }

    public function filters_where($where)
    {
        global $pagenow, $wpdb;
        if(is_admin() && $pagenow == 'edit.php' && !empty($_GET['post_type']) && $_GET['post_type'] == $this->PT && !empty($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_EMAIL))
        {
            $where = preg_replace("/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*('[^']+')\s*\)/", "(" . $wpdb->posts . ".`post_title` LIKE $1) OR (".$wpdb->postmeta.".`meta_key`='lsd_guest_email' AND ".$wpdb->postmeta.".`meta_value` LIKE $1)", $where);
        }

        return $where;
    }
}

endif;