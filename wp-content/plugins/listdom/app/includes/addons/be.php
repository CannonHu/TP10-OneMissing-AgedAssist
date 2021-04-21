<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Addons_BE')):

/**
 * Listdom Addon Block Editor Class.
 *
 * @class LSD_Addons_BE
 * @version	1.0.0
 */
class LSD_Addons_BE extends LSD_Addons
{
    private $settings;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        // Listdom Settings
        $this->settings = LSD_Options::settings();
	}
    
    public function init()
    {
        // Block Editor is not Available!
        if(!function_exists('register_block_type')) return;

        // Block Editor Category
        add_filter('block_categories', array($this, 'cateogry'), 9999);

        // Enable Listdom Assets on Block Editor
        add_filter('lsd_should_include_backend', array($this, 'should_include'), 9999);

        // Include Assets
        add_action('lsd_admin_assets', array($this, 'assets'), 9999);

        // Block Editor Status for Post Types
        foreach(array('listing', 'shortcode', 'search') as $post_type)
        {
            add_filter('lsd_ptype_'.$post_type.'_args', array($this, 'enable_be'), 9999);
        }

        // Block Editor Status for Taxonomies
        foreach(array('location', 'tag', 'feature', 'label') as $taxonomy)
        {
            add_filter('lsd_taxonomy_'.$taxonomy.'_args', array($this, 'enable_be'), 9999);
        }
    }

    public function cateogry($categories)
    {
        $categories = array_merge(array
            (array(
                'slug' => 'lsd.be.category',
                'title' => esc_html__('Listdom', 'listdom'),
                'icon' => 'list-view'
            )),
            $categories
        );

        return $categories;
    }

    public function should_include($include)
    {
        // Current Screen
        $screen = get_current_screen();

        // It's Blockeditor
        if(method_exists($screen, 'is_block_editor') and $screen->is_block_editor()) $include = true;

        return $include;
    }

    public function assets()
    {
        // Current Screen
        $screen = get_current_screen();

        // Is it block editor page?
        if(method_exists($screen, 'is_block_editor') and $screen->is_block_editor())
        {
            // Include Listdom Block Dependencies
            wp_enqueue_script('lsd-blockeditor', $this->lsd_asset_url('js/blockeditor.min.js'), array('wp-blocks', 'wp-element'));

            register_block_type('listdom/shortcodes', array(
                'editor_script' => 'lsd-blockeditor',
            ));

            // Localize
            wp_localize_script('lsd-blockeditor', 'lsd', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'shortcodes' => $this->getShortcodes(true),
            ));
        }
    }

    public function enable_be($args)
    {
        // Block Editor Enabled for Listings
        if(isset($this->settings['blockeditor_status']) and $this->settings['blockeditor_status'])
        {
            $args['show_in_rest'] = true;
        }

        return $args;
    }
}

endif;