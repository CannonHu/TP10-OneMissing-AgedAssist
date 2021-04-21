<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('Listdom')):

/**
 * Main Listdom Class.
 *
 * @class Listdom
 * @version	1.0.0
 */
final class Listdom
{
    /**
	 * Listdom version.
	 *
	 * @var string
	 */
	public $version = '2.1.0';
    
    /**
	 * The single instance of the class.
	 *
	 * @var Listdom
	 * @since 1.0.0
	 */
	protected static $instance = null;
    
    /**
	 * Main Listdom Instance.
	 *
	 * Ensures only one instance of Listdom is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Listdom()
	 * @return Listdom - Main instance.
	 */
	public static function instance()
    {
        // Get an instance of Class
		if(is_null(self::$instance)) self::$instance = new self();
        
        // Return the instance
		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 1.0.0
	 */
	public function __clone()
    {
		_doing_it_wrong(__FUNCTION__, esc_html__('Cheating huh?', 'listdom'), '1.0.0');
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.0.0
	 */
	public function __wakeup()
    {
		_doing_it_wrong(__FUNCTION__, esc_html__('Cheating huh?', 'listdom'), '1.0.0');
	}
    
    /**
	 * Listdom Constructor.
	 */
	protected function __construct()
    {
        // Define Constants
        $this->define_constants();
        
        // Auto Loader
        spl_autoload_register(array($this, 'autoload'));
        
        // Initialize the Listdom
        $this->init();

        // Include Helper Functions
        $this->helpers();
        
        // Do listdom_loaded action
		do_action('listdom_loaded');
	}
    
    /**
	 * Define Listdom Constants.
	 */
	private function define_constants()
    {
        // Listdom Absolute Path
        if(!defined('LSD_ABSPATH')) define('LSD_ABSPATH', dirname(__FILE__));

        // Listdom Directory Name
        if(!defined('LSD_DIRNAME')) define('LSD_DIRNAME', basename(LSD_ABSPATH));

        // Listdom Plugin Base Name
        if(!defined('LSD_BASENAME')) define('LSD_BASENAME', plugin_basename(LSD_ABSPATH.'/listdom.php')); // listdom/listdom.php or listdom-pro/listdom.php

        // Listdom Version
        if(!defined('LSD_VERSION')) define('LSD_VERSION', $this->version);

        // Listdom Update Server
        if(!defined('LSD_UPDATE_SERVER')) define('LSD_UPDATE_SERVER', 'https://totalery.com/api/update/listdom/server.php');

        // WordPress Upload Directory
		$upload_dir = wp_upload_dir();

		// Listdom Logs Directory
        if(!defined('LSD_LOG_DIR')) define('LSD_LOG_DIR', $upload_dir['basedir'] . '/lsd-logs/');

        // Listdom Upload Directory
        if(!defined('LSD_UP_DIR'))
        {
            define('LSD_UP_DIR', $upload_dir['basedir'] . '/listdom/');
        }

        // Listdom Map Providers
        if(!defined('LSD_MP_GOOGLE')) define('LSD_MP_GOOGLE', 'googlemap');
        if(!defined('LSD_MP_LEAFLET')) define('LSD_MP_LEAFLET', 'leaflet');
	}
    
    /**
     * Initialize the Listdom
     */
    private function init()
    {
        // LSD Main
        $main = new LSD_Main();

        // Plugin Activation / Deactivation / Uninstall
        LSD_Plugin_Hooks::instance();

        // Listdom Kses
        $Kses = new LSD_Kses();
        $Kses->init();
        
        // Listdom Menus
        $Menus = new LSD_Menus();
        $Menus->init();
        
        // Listdom Post Types
        $PTypes = new LSD_PTypes();
        $PTypes->init();

        // Listdom Taxonomies
        $Taxonomies = new LSD_Taxonomies();
        $Taxonomies->init();

        // Listdom Author
        $Author = new LSD_Author();
        $Author->init();

        // Listdom Actions / Filters
        $Hooks = new LSD_Hooks();
        $Hooks->init();

        // Flush WordPress rewrite rules only if needed
        LSD_RewriteRules::flush();

        // Listdom Assets
        $Assets = new LSD_Assets();
        $Assets->init();

        // Listdom Social Networks
        $Socials = new LSD_Socials();
        $Socials->init();

        // Listdom Skins
        $Skins = new LSD_Skins();
        $Skins->init();

        // Listdom Shortcodes
        $Shortcodes = new LSD_Shortcodes();
        $Shortcodes->init();

        // Listdom Widgets
        $Widgets = new LSD_Widgets();
        $Widgets->init();

        // Listdom Addons
        $Addons = new LSD_Addons();
        $Addons->init();

        // Listdom Endpoints
        $Endpoints = new LSD_Endpoints();
        $Endpoints->init();

        // Listdom Compatibility
        $Compatibility = new LSD_Compatibility();
        $Compatibility->init();

        // Listdom Internationalization
        $i18n = new LSD_i18n();
        $i18n->init();

        // Listdom Notifications
        $Notifications = new LSD_Notifications();
        $Notifications->init();

        // Listdom AJAX
        $Ajax = new LSD_Ajax();
        $Ajax->init();

        // Listdom Dummy Data
        $Dummy = new LSD_Dummy();
        $Dummy->init();

        // Listdom Search
        $Search = new LSD_Search();
        $Search->init();

        // Dashboard
        $Dashboard = new LSD_Dashboard();
        $Dashboard->init();

        // Upgrade
        $Upgrade = new LSD_Upgrade();
        $Upgrade->init();

        // Initialize Pro Features
        if($main->isPro())
        {
            $Pro = new LSD_Pro();
            $Pro->init();
        }
        // Initialize Lite Features
        else
        {
            $Lite = new LSD_Lite();
            $Lite->init();
        }

        // REST API
        $API = new LSD_API();
        $API->init();
    }

    /**
     * Include Helper Functions
     */
    public function helpers()
    {
        // Template Functions
        require_once 'app/includes/helpers/templates.php';

        // Util Functions
        require_once 'app/includes/helpers/util.php';
    }
    
    /**
     * Automatically load Listdom classes whenever needed.
     * @param string $class_name
     * @return void
     */
    private function autoload($class_name)
    {
        $class_ex = explode('_', strtolower($class_name));
        
        // It's not a Listdom Class
        if($class_ex[0] != 'lsd') return;
        
        // Drop 'LSD'
        $class_path = array_slice($class_ex, 1);
        
        // Create Class File Path
        $file_path = LSD_ABSPATH . '/app/includes/' . implode('/', $class_path) . '.php';
        
        // We found the class!
        if(file_exists($file_path)) require_once $file_path;
    }
    
    /**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	public function is_request($type)
    {
		switch($type)
        {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined('DOING_AJAX');
			case 'cron':
				return defined('DOING_CRON');
			case 'frontend':
				return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
            default:
                return false;
		}
	}
}

endif;

/**
 * Main instance of Listdom.
 *
 * Returns the main instance of Listdom to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Listdom
 */
function listdom()
{
	return Listdom::instance();
}

// Init the Listdom :)
listdom();