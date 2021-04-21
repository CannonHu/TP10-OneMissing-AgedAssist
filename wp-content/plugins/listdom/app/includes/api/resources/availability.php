<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API_Resources_Availability')):

/**
 * Listdom API Availability Resource Class.
 *
 * @class LSD_API_Resources_Availability
 * @version	1.0.0
 */
class LSD_API_Resources_Availability extends LSD_API_Resource
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function get($id)
    {
        $availability = get_post_meta($id, 'lsd_ava', true);
        if(!is_array($availability)) return NULL;

        $rendered = array();
        foreach(LSD_Main::get_weekdays() as $weekday)
        {
            $daycode = $weekday['code'];
            $hours = NULL;
            $off = 0;

            if(isset($availability[$daycode]) and isset($availability[$daycode]['off']) and $availability[$daycode]['off'])
            {
                $hours = esc_html__('Off', 'listdom');
                $off = 1;
            }
            elseif(isset($availability[$daycode]) and isset($availability[$daycode]['hours'])) $hours = $availability[$daycode]['hours'];

            $rendered[$daycode] = array(
                'day' => esc_html__($weekday['day'], 'listdom'),
                'hours' => $hours,
                'off' => $off,
            );
        }

        return apply_filters('lsd_api_resource_availability', $rendered, $id);
	}

    public static function collection($ids)
    {
        $items = array();
        foreach($ids as $id) $items[] = self::get($id);

        return $items;
    }
}

endif;