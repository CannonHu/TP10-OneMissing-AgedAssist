<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Map_Provider')):

/**
 * Listdom Map Provider Class.
 *
 * @class LSD_Map_Provider
 * @version	1.0.0
 */
class LSD_Map_Provider extends LSD_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function get($provider = NULL)
    {
        // Provider is Valid
        if(trim($provider) and LSD_Map_Provider::valid($provider)) return $provider;

        // Return Default Provider
        $settings = LSD_Options::settings();
        return (isset($settings['map_provider']) ? $settings['map_provider'] : LSD_MP_GOOGLE);
    }

    public static function get_providers()
    {
        // Map Providers of Lite Version
        if(LSD_Base::isLite())
        {
            return array(
                LSD_MP_GOOGLE=>esc_html__('Google Maps', 'listdom')
            );
        }
        // Map Providers of Full Version
        else
        {
            return array(
                LSD_MP_LEAFLET=>esc_html__('Leaflet', 'listdom'),
                LSD_MP_GOOGLE=>esc_html__('Google Maps', 'listdom')
            );
        }
	}

    public function form($shape)
    {
        // Listdom Settings
        $settings = LSD_Options::settings();

        // Map Provider
        if(LSD_Map_Provider::def() === LSD_MP_GOOGLE) $file = 'metaboxes/listing/map/googlemap.php';
        else $file = 'metaboxes/listing/map/leaflet.php';

        // Generate output
        $output = $this->include_html_file($file, array(
            'return_output' => true,
            'parameters' => array(
                'settings' => $settings,
                'shape' => $shape
            ),
        ));

        // Add to Footer
        LSD_Assets::footer($output);
	}

    public static function valid($provider)
    {
        $providers = LSD_Map_Provider::get_providers();
        return isset($providers[$provider]);
	}

    public static function def()
    {
        return LSD_Map_Provider::get();
	}
}

endif;