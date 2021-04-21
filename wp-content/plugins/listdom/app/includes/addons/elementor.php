<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Addons_Elementor')):

/**
 * Listdom Addon Elementor Class.
 *
 * @class LSD_Addons_Elementor
 * @version	1.0.0
 */
class LSD_Addons_Elementor extends LSD_Addons
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
        // Register Widgets
        add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'), 10);
    }

    /**
     * Register Other Widgets
     * @param Elementor\Widgets_Manager $widget_manager
     */
    public function register_widgets($widget_manager)
    {
    }
}

endif;