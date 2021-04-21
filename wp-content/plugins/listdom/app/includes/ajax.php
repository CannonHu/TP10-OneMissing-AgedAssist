<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Ajax')):

/**
 * Listdom General AJAX Class.
 *
 * @class LSD_Ajax
 * @version	1.0.0
 */
class LSD_Ajax extends LSD_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}
    
    public function init()
    {
        // Get Map Objects
        add_action('wp_ajax_lsd_map_search', array($this, 'map_search'));
        add_action('wp_ajax_nopriv_lsd_map_search', array($this, 'map_search'));

        // AutoSuggest
        add_action('wp_ajax_lsd_autosuggest', array($this, 'autosuggest'));
        add_action('wp_ajax_nopriv_lsd_autosuggest', array($this, 'autosuggest'));
    }

    public function map_search()
    {
        $args = (isset($_POST['args']) and is_array($_POST['args'])) ? $_POST['args'] : array();

        // Sanitization
        array_walk_recursive($args, 'sanitize_text_field');

        $atts = (isset($args['atts']) and is_array($args['atts'])) ? $args['atts'] : array();

        // Listdom Shortcode
        $LSD = new LSD_Shortcodes_Listdom();

        // Skin
        $skin = (isset($atts['lsd_display']) and isset($atts['lsd_display']['skin'])) ? sanitize_text_field($atts['lsd_display']['skin']) : $LSD->get_default_skin();

        // Get Skin Object
        $SKO = $LSD->SKO($skin);

        // Start the skin
        $SKO->start($atts);
        $SKO->after_start();

        // Current View
        $SKO->setField('default_view', (isset($_POST['view']) ? sanitize_text_field($_POST['view']) : 'grid'));

        // Generate the Query
        $SKO->query();

        // Apply Search
        $SKO->apply_search($_POST, 'map');

        // Get Map Objects
        $archive = new LSD_PTypes_Listing_Archive();
        $objects = $archive->render_map_objects($SKO->search(), $args);

        // Change the limit
        $SKO->setLimit('listings');

        // Get Listings
        $IDs = $SKO->search();
        $SKO->setField('listings', $IDs);

        $listings = $SKO->listings_html();
        $total = $SKO->getField('found_listings');
        $next_page = $SKO->getField('next_page');

        $this->response(array('objects'=>$objects, 'listings'=>LSD_Kses::page($listings), 'next_page'=>$next_page, 'count'=>count($IDs), 'total'=>$total));
    }

    public function autosuggest()
    {
        // Check if security nonce is set
        if(!isset($_REQUEST['_wpnonce'])) $this->response(array('success'=>0, 'message'=>esc_html__("Security nonce is required.", 'listdom')));

        // Verify that the nonce is valid
        if(!wp_verify_nonce(sanitize_text_field($_REQUEST['_wpnonce']), 'lsd_autosuggest')) $this->response(array('success'=>0, 'message'=>esc_html__("Security nonce is invalid.", 'listdom')));

        $term = isset($_REQUEST['term']) ? sanitize_text_field($_REQUEST['term']) : '';
        $source = isset($_REQUEST['source']) ? sanitize_text_field($_REQUEST['source']) : '';

        $found = false;
        $items = '<ul class="lsd-autosuggest-items">';

        if($source === 'users')
        {
            $users = get_users(array(
                'search' => '*'.$term.'*',
                'search_columns' => array('user_email', 'display_name'),
                'number' => 10,
            ));

            foreach($users as $user)
            {
                $found = true;
                $items .= '<li data-value="'.esc_attr($user->ID).'">'.$user->user_email.'</li>';
            }
        }
        else
        {
            $posts = get_posts(array(
                'post_type' => $source,
                's' => $term,
                'numberposts' => 10,
                'post_status' => 'publish'
            ));

            foreach($posts as $post)
            {
                $found = true;
                $items .= '<li data-value="'.esc_attr($post->ID).'">'.$post->post_title.'</li>';
            }
        }

        $items .= '</ul>';

        $this->response(array(
            'success' => ((int) $found),
            'items' => $items,
        ));
    }
}

endif;