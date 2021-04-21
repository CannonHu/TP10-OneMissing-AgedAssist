<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Assets')):

/**
 * Listdom Assets Class.
 *
 * @class LSD_Assets
 * @version	1.0.0
 */
class LSD_Assets extends LSD_Base
{
    /**
     * @static
     * @var array
     */
    public static $params = array();
    public $settings = array();

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        // General Settings
        $this->settings = LSD_Options::settings();
	}
    
    public function init()
    {
        // Include needed assets (CSS, JavaScript etc) in the WordPress backend
        add_action('admin_enqueue_scripts', array($this, 'admin'), 0);
        
        // Include needed assets (CSS, JavaScript etc) in the WordPress frontend
        add_action('wp_enqueue_scripts', array($this, 'site'), 0);

        // Add custom styles to header
        add_action('wp_head', array($this, 'CSS'), 9999);

        // Register Listdom function to be called in WordPress footer hook
        if(is_admin()) add_action('admin_footer', array($this, 'load_footer'), 9999);
        else add_action('wp_footer', array($this, 'load_footer'), 9999);

        // Load Google Maps async
        add_filter('script_loader_tag', array($this, 'async_googlemaps'), 99, 2);
    }
    
    public function site()
    {
        // Check to see if we should include the assets or not
        if(!$this->should_include('frontend')) return;

        // Include Listdom frontend script file
        wp_enqueue_script('lsd-frontend', $this->lsd_asset_url('js/frontend.min.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), $this->version(), true);

        // Localize Vars
        wp_localize_script('lsd-frontend','lsd', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'timepicker_format' => (isset($this->settings['timepicker_format']) ? (int) $this->settings['timepicker_format'] : 24),
        ));

        // Include Listdom frontend CSS file
        wp_enqueue_style('lsd-frontend', $this->lsd_asset_url('css/frontend.css'), array(), $this->version());

        // RTL Style
        if(is_rtl()) wp_enqueue_style('lsd-frontend-rtl', $this->lsd_asset_url('css/frontend-rtl.css'), array('lsd-frontend'), $this->version());

        // Include Personalize Assets
        $personalize = new LSD_Personalize();
        $personalize->assets();

        // Include Listdom font-awesome assets
        $this->fontawesome();

        // Include OWL
        $this->owl(array('lsd-frontend'));

        // Include Lightbox
        $this->lightbox();
		
		// Include Select2
        $this->select2();
    }
    
    public function admin()
    {
        // Check to see if we should include the assets or not
        if(!$this->should_include('backend')) return;

        // Include Listdom backend script file
        wp_enqueue_script('lsd-backend', $this->lsd_asset_url('js/backend.min.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable'), $this->version(), true);

        // Localize Vars
        wp_localize_script('lsd-backend','lsd', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'timepicker_format' => (isset($this->settings['timepicker_format']) ? (int) $this->settings['timepicker_format'] : 24),
        ));

        // Include Listdom backend CSS file
        wp_enqueue_style('lsd-backend', $this->lsd_asset_url('css/backend.css'), array(), $this->version());

        // WordPress Media
        $this->media();

        // Include Listdom font-awesome assets
        $this->fontawesome();

        // Include Color Picker
        $this->colorpicker();

        // Include Assets
        do_action('lsd_admin_assets');
    }

    public function CSS()
    {
        // Add Custom Styles
        $styles = LSD_Options::styles();

        // There is no Custom Styles
        if(!trim($styles['CSS'])) return;

        $CSS = strip_tags($styles['CSS']);
        echo '<style type="text/css">'.stripslashes($CSS).'</style>';
    }

    public function fontawesome()
    {
        // Fontawesome is disabled!
        if(isset($this->settings['fontawesome_status']) and !$this->settings['fontawesome_status']) return false;

        // Include the font icons
        wp_enqueue_style('fontawesome', $this->lsd_asset_url('packages/font-awesome/css/font-awesome.min.css'), array(), LSD_Assets::version());
        return true;
    }

    public function owl($deps = array())
    {
        // Scripts
        wp_enqueue_script('owl', $this->lsd_asset_url('packages/owl-carousel/owl.carousel.min.js'), $deps, LSD_Assets::version());
    }

    public function isotope()
    {
        // Scripts
        wp_enqueue_script('isotope', $this->lsd_asset_url('packages/isotope/isotope.pkgd.min.js'), array(), LSD_Assets::version());
    }

    public function grecaptcha()
    {
        // Scripts
        wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
    }

    public function iconpicker()
    {
        // Include the iconpicker JS file
        wp_enqueue_script('jquery-fonticonpicker', $this->lsd_asset_url('packages/font-iconpicker/jquery.fonticonpicker.min.js'), array(), LSD_Assets::version());
    }

    public function colorpicker()
    {
        // Include WordPress color picker JavaScript file
        wp_enqueue_script('wp-color-picker');

        // Include WordPress color picker CSS file
        wp_enqueue_style('wp-color-picker');
    }

    public static function select2()
    {
        $base = new LSD_Base();

        // Include Select2 JavaScript file
        wp_enqueue_script('select2', $base->lsd_asset_url('packages/select2/select2.full.min.js'), array(), LSD_Assets::version());

        // Include Select2 CSS file
        wp_enqueue_style('select2', $base->lsd_asset_url('packages/select2/select2.min.css'), array(), LSD_Assets::version());
    }
	
	public static function lightbox()
    {
        $base = new LSD_Base();

        // Include Lightbox JavaScript file
        wp_enqueue_script('featherlight', $base->lsd_asset_url('packages/featherlight/fl.min.js'), array(), LSD_Assets::version());

        // Include Lightbox CSS file
        wp_enqueue_style('featherlight', $base->lsd_asset_url('packages/featherlight/fl.min.css'), array(), LSD_Assets::version());
    }

    public static function media()
    {
        // WordPress Media Thickbox
        wp_enqueue_media();
    }

    public function moment()
    {
        // Include Moment JavaScript file
        wp_enqueue_script('moment', $this->lsd_asset_url('packages/moment/moment.min.js'), array(), LSD_Assets::version());
    }

    public function daterangepicker()
    {
        // Include Date Range Picker JavaScript file
        wp_enqueue_script('date-range-picker', $this->lsd_asset_url('packages/date-range-picker/drp.min.js'), array('moment', 'lsd-frontend'), LSD_Assets::version());

        // Include Date Range Picker CSS file
        wp_enqueue_style('date-range-picker', $this->lsd_asset_url('packages/date-range-picker/drp.min.css'), array('lsd-frontend'), LSD_Assets::version());
    }

    public static function api()
    {
        $base = new LSD_Base();

        // Dependencies
        $dependencies = array();

        if(is_admin()) $dependencies[] = 'lsd-backend';
        else $dependencies[] = 'lsd-frontend';

        // Enqueue the JS API
        wp_enqueue_script('lsd-map-upsert', $base->lsd_asset_url('js/map.upsert.min.js'), $dependencies, LSD_Assets::version(), true);
    }

    public static function map($draw = false)
    {
        // Map Provider
        $provider = LSD_Map_Provider::get();

        if($provider === LSD_MP_GOOGLE) self::googlemaps();
        elseif($provider === LSD_MP_LEAFLET) self::leaflet($draw);
    }

    public static function leaflet($draw = false)
    {
        // Include Leaflet Javascript API
        $leaflet_include = apply_filters('lsd_leaflet_include', true);
        if(!$leaflet_include) return false;

        $base = new LSD_Base();

        // Dependencies
        $dependencies = array();

        if(is_admin()) $dependencies[] = 'lsd-backend';
        else $dependencies[] = 'lsd-frontend';

        // Enqueue the CSS
        wp_enqueue_style('leaflet', $base->lsd_asset_url('packages/leaflet/leaflet.css'), array(), LSD_Assets::version());

        // Enqueue the JS API
        wp_enqueue_script('leaflet', $base->lsd_asset_url('packages/leaflet/leaflet.js'), $dependencies, LSD_Assets::version());

        if($draw)
        {
            // Add Leaflet to Dependencies
            $dependencies[] = 'leaflet';

            // Enqueue the CSS
            wp_enqueue_style('leaflet-draw', $base->lsd_asset_url('packages/leaflet/leaflet.draw.css'), array(), LSD_Assets::version());

            // Enqueue the JS API
            wp_enqueue_script('leaflet-draw', $base->lsd_asset_url('packages/leaflet/leaflet.draw.js'), $dependencies, LSD_Assets::version());
        }

        $omnivore = apply_filters('lsd_leaflet_omnivore_include', false);
        if($omnivore)
        {
            // Add Leaflet to Dependencies
            $dependencies[] = 'leaflet';

            wp_enqueue_script('leaflet-omnivore', $base->lsd_asset_url('packages/leaflet/leaflet.omnivore.js'), $dependencies, LSD_Assets::version());
        }

        return true;
    }

    public static function googlemaps()
    {
        // Listdom Settings
        $settings = LSD_Options::settings();

        // Include Google Maps Javascript API
        $gm_include = apply_filters('lsd_gm_include', true);
        if(!$gm_include) return false;

        // Dependencies
        $dependencies = array();

        if(is_admin()) $dependencies[] = 'lsd-backend';
        else $dependencies[] = 'lsd-frontend';

        // Enqueue the API
        wp_enqueue_script('googlemaps', '//maps.googleapis.com/maps/api/js?libraries=places,drawing&callback=listdom_googlemaps_callback'.((isset($settings['googlemaps_api_key']) and trim($settings['googlemaps_api_key'])) ? '&key='.urlencode($settings['googlemaps_api_key']) : ''), $dependencies);

        return true;
    }

    public function async_googlemaps($tag, $handle)
    {
        if('googlemaps' !== $handle and 'richmarker-script' !== $handle) return $tag;

        return str_replace(' src=', ' async="async" defer="defer" src=', $tag);
    }

    public function get_googlemap_style($style)
    {
        // Get Style Path
        $path = $this->lsd_asset_path('map-styles/'.$style.'.json');

        // File Does Not Exists
        if(!LSD_File::exists($path)) return apply_filters('lsd_mapstyles_json', "''", $style);

        // Return the Style
        return LSD_File::read($path);
    }

    public static function footer($string)
    {
        return self::params($string, 'footer');
    }

    public static function params($string, $key = 'footer')
    {
        $string = (string) $string;
        if(trim($string) == '') return;

        // Register the key for removing PHP notices
        if(!isset(self::$params[$key])) self::$params[$key] = array();

        // Add it to the params
        array_push(self::$params[$key], $string);
    }

    public function load_footer()
    {
        if(!isset(self::$params['footer']) or (isset(self::$params['footer']) and !count(self::$params['footer']))) return;

        // Remove duplicate strings
        $strings = array_unique(self::$params['footer']);

        // Print the assets in the footer
        foreach($strings as $string) echo PHP_EOL.$string.PHP_EOL;
    }

    public function should_include($client = 'frontend')
    {
        // Always return true for frontend
        if($client == 'frontend') return true;
        else
        {
            // Current Screen
            $screen = get_current_screen();

            $base = $screen->base;
            $post_type = $screen->post_type;
            $taxonomy = $screen->taxonomy;

            // It's one of Listdom taxonomy pages
            if(trim($taxonomy) and in_array($taxonomy, $this->taxonomies())) return true;

            // It's one of Listdom post type pages
            if(trim($post_type) and in_array($post_type, $this->postTypes())) return true;

            // It's one of Listdom pages or the pages that Listdom should work fine
            if(trim($base) and in_array($base, array(
                'listdom_page_listdom-settings',
                'listdom_page_listdom-ix',
                'toplevel_page_listdom',
                'widgets',
            ))) return true;

            return apply_filters('lsd_should_include_backend', false);
        }
    }

    public static function version()
    {
        $version = LSD_VERSION;
        if(defined('WP_DEBUG') and WP_DEBUG) $version .= '.'.time();

        return $version;
    }
}

endif;