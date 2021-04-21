<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Dashboard')):

/**
 * Listdom Dashboard Class.
 *
 * @class LSD_Dashboard
 * @version	1.0.0
 */
class LSD_Dashboard extends LSD_Base
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
        // Dashboard Shortcode
        $Dashboard = new LSD_Shortcodes_Dashboard();
        $Dashboard->init();
    }

    public function modules()
    {
        $modules = array(
            array('label' => esc_html__('Address / Map', 'listdom'), 'key' => 'address'),
            array('label' => esc_html__('Price Options', 'listdom'), 'key' => 'price'),
            array('label' => esc_html__('Work Hours', 'listdom'), 'key' => 'availability'),
            array('label' => esc_html__('Contact Details', 'listdom'), 'key' => 'contact'),
            array('label' => esc_html__('Remark', 'listdom'), 'key' => 'remark'),
            array('label' => esc_html__('Gallery', 'listdom'), 'key' => 'gallery'),
            array('label' => esc_html__('Attributes', 'listdom'), 'key' => 'attributes'),
            array('label' => esc_html__('Locations', 'listdom'), 'key' => 'locations'),
            array('label' => esc_html__('Tags', 'listdom'), 'key' => 'tags'),
            array('label' => esc_html__('Features', 'listdom'), 'key' => 'features'),
            array('label' => esc_html__('Labels', 'listdom'), 'key' => 'labels'),
            array('label' => esc_html__('Featured Image', 'listdom'), 'key' => 'image'),
            array('label' => esc_html__('Embed Codes', 'listdom'), 'key' => 'embed')
        );

        return apply_filters('lsd_dashboard_modules', $modules);
    }
}

endif;