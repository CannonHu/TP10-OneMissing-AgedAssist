<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Main')):

/**
 * Listdom Main Class.
 *
 * @class LSD_Main
 * @version	1.0.0
 */
class LSD_Main extends LSD_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

	public function get_installed_db_version()
    {
        $installed_db_ver = get_option('lsd_db_version');
        if(trim($installed_db_ver) == '') $installed_db_ver = 0;

        return $installed_db_ver;
    }

    public function is_db_update_required()
    {
        $installed_db_ver = $this->get_installed_db_version();
        return version_compare($installed_db_ver, LSD_Base::DB_VERSION, '<');
    }

    public function geopoint($address)
    {
        $address = urlencode($address);

        // Listdom Settings
        $settings = LSD_Options::settings();

        $url1 = "https://nominatim.openstreetmap.org/search?format=json&q=".$address;
        $url2 = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address.((isset($settings['googlemaps_api_key']) and trim($settings['googlemaps_api_key']) != '') ? '?key='.esc_url($settings['googlemaps_api_key']) : '');
        $url3 = 'http://www.datasciencetoolkit.org/maps/api/geocode/json?sensor=false&address='.$address;

        // Getting Geo Point Using First URL
        $JSON = LSD_Main::download($url1, array(
            'timeout' => 10,
            'user-agent' => $_SERVER['HTTP_USER_AGENT'],
            'sslverify' => false
        ));

        $data = json_decode($JSON, true);
        $place = isset($data[0]) ? $data[0] : NULL;

        if((isset($place['lat']) and $place['lat']) and (isset($place['lon']) and $place['lon'])) return array($place['lat'], $place['lon']);

        // Getting Geo Point Using Second URL
        $JSON = LSD_Main::download($url2, array(
            'timeout' => 10,
            'user-agent' => $_SERVER['HTTP_USER_AGENT'],
            'sslverify' => false
        ));

        $data = json_decode($JSON, true);
        $geopoint = isset($data['results'][0]) ? $data['results'][0]['geometry']['location'] : NULL;

        if((isset($geopoint['lat']) and $geopoint['lat']) and (isset($geopoint['lng']) and $geopoint['lng'])) return array($geopoint['lat'], $geopoint['lng']);

        // Getting Geo Point Using Third URL
        $JSON = LSD_Main::download($url3, array(
            'timeout' => 10,
            'user-agent' => $_SERVER['HTTP_USER_AGENT'],
            'sslverify' => false
        ));

        $data = json_decode($JSON, true);
        $geopoint = isset($data['results'][0]) ? $data['results'][0]['geometry']['location'] : NULL;

        if((isset($geopoint['lat']) and $geopoint['lat']) and (isset($geopoint['lng']) and $geopoint['lng'])) return array($geopoint['lat'], $geopoint['lng']);
        else return array(0, 0);
    }

    /**
     * Returns weekdays
     * @return array
     */
    public static function get_weekdays()
    {
        $week_start = LSD_Main::get_first_day_of_week();

        /**
         * Don't change it to translate-able strings
         */
        $raw = array(
            array('day'=>'Sunday', 'code'=>7, 'label'=>esc_html__('Sunday', 'listdom')),
            array('day'=>'Monday', 'code'=>1, 'label'=>esc_html__('Monday', 'listdom')),
            array('day'=>'Tuesday', 'code'=>2, 'label'=>esc_html__('Tuesday', 'listdom')),
            array('day'=>'Wednesday', 'code'=>3, 'label'=>esc_html__('Wednesday', 'listdom')),
            array('day'=>'Thursday', 'code'=>4, 'label'=>esc_html__('Thursday', 'listdom')),
            array('day'=>'Friday', 'code'=>5, 'label'=>esc_html__('Friday', 'listdom')),
            array('day'=>'Saturday', 'code'=>6, 'label'=>esc_html__('Saturday', 'listdom')),
        );

        $labels = array_slice($raw, $week_start);
        $rest = array_slice($raw, 0, $week_start);

        foreach($rest as $label) array_push($labels, $label);
        return apply_filters('lsd_weekdays', $labels);
    }

    /**
     * Get First of The Week from WordPress Options
     * @return mixed
     */
    public static function get_first_day_of_week()
    {
        return get_option('start_of_week', 1);
    }

    public static function grecaptcha_field()
    {
        // Listdom Options
        $settings = LSD_Options::settings();

        // Recaptcha is not enabled!
        if(!isset($settings['grecaptcha_status']) or (isset($settings['grecaptcha_status']) and !$settings['grecaptcha_status'])) return NULL;

        // Site Key
        $sitekey = (isset($settings['grecaptcha_sitekey']) and trim($settings['grecaptcha_sitekey'])) ? $settings['grecaptcha_sitekey'] : NULL;

        // Site key is empty!
        if(!$sitekey) return NULL;

        // Include JS Library
        $assets = new LSD_Assets();
        $assets->grecaptcha();

        return '<div class="g-recaptcha" data-sitekey="'.esc_attr($sitekey).'"></div>';
    }

    public static function grecaptcha_check($g_recaptcha_response, $remote_ip = NULL)
    {
        // Listdom Options
        $settings = LSD_Options::settings();

        // Recaptcha is not enabled!
        if(!isset($settings['grecaptcha_status']) or (isset($settings['grecaptcha_status']) and !$settings['grecaptcha_status'])) return true;

        // Secret Key
        $secretkey = (isset($settings['grecaptcha_secretkey']) and trim($settings['grecaptcha_secretkey'])) ? $settings['grecaptcha_secretkey'] : NULL;

        // Secret key is empty!
        if(!$secretkey) return false;

        // Get the IP
        if(is_null($remote_ip)) $remote_ip = (isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : '');

        // Data
        $data = array('secret'=>$secretkey, 'remoteip'=>$remote_ip, 'response'=>$g_recaptcha_response);

        // Request
        $request = "";
        foreach($data as $key=>$value) $request .= $key.'='.urlencode(stripslashes($value)).'&';

        // Validating the re-captcha
        $JSON = LSD_Main::download("https://www.google.com/recaptcha/api/siteverify?".trim($request, '& '));
        $response = json_decode($JSON, true);

        if(isset($response['success']) and trim($response['success'])) return true;
        else return false;
    }

    public static function download($url, $args = array())
    {
        return wp_remote_retrieve_body(wp_remote_get($url, $args));
    }

    public static function assign($post_id, $user_id)
    {
        // DB Library
        $db = new LSD_db();

        // Assign Listing
        $db->q("UPDATE `#__posts` SET `post_author`='$user_id' WHERE `ID`='$post_id'");
    }

    public function standardize_format($date, $from, $to = 'Y-m-d')
    {
        if(!trim($date)) return '';

        $date = str_replace('.', '-', $date);
        if($from === 'dd/mm/yyyy')
        {
            $d = explode('/', $date);
            $date = $d[2].'-'.$d[1].'-'.$d[0];
        }

        return date($to, strtotime($date));
    }

    public function jstophp_format($js_format = 'yyyy-mm-dd')
    {
        if($js_format === 'dd-mm-yyyy') $php_format = 'd-m-Y';
        elseif($js_format === 'yyyy/mm/dd') $php_format = 'Y/m/d';
        elseif($js_format === 'dd/mm/yyyy') $php_format = 'd/m/Y';
        elseif($js_format === 'yyyy.mm.dd') $php_format = 'Y.m.d';
        elseif($js_format === 'dd.mm.yyyy') $php_format = 'd.m.Y';
        else $php_format = 'Y-m-d';

        return $php_format;
    }
}

endif;