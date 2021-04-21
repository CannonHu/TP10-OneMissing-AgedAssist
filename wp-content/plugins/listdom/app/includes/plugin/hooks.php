<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Plugin_Hooks')):

/**
 * Listdom Plugin Hooks Class.
 *
 * @class LSD_Plugin_Hooks
 * @version	1.0.0
 */
class LSD_Plugin_Hooks
{
    /**
	 * The single instance of the class.
	 *
	 * @var LSD_Plugin_Hooks
	 * @since 1.0.0
	 */
	protected static $instance = null;

	public $main;
	public $db;

    /**
	 * Listdom Plugin Hooks Instance.
	 *
	 * @since 1.0.0
	 * @static
	 * @return LSD_Plugin_Hooks
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
	 * Constructor method
	 */
	protected function __construct()
    {
        register_activation_hook(LSD_BASENAME, array($this, 'activate'));
		register_deactivation_hook(LSD_BASENAME, array($this, 'deactivate'));
		register_uninstall_hook(LSD_BASENAME, array('LSD_Plugin_Hooks', 'uninstall'));
        
        // Main Class
        $this->main = new LSD_Main();
        
        // DB Class
        $this->db = new LSD_db();
	}
    
    /**
     * Runs on plugin activation
     * @param boolean $network
     */
    public function activate($network = false)
	{
        // Redirect user to Listdom Dashboard
        add_option('lsd_activation_redirect', true);

        $current_blog_id = get_current_blog_id();
        
        // Plugin activated only for one blog
        if(!function_exists('is_multisite') or (function_exists('is_multisite') and !is_multisite())) $network = false;
        if(!$network)
        {
            $this->install($current_blog_id);

            // Add WordPress flush rewrite rules in to do list
            LSD_RewriteRules::todo();
            
            // Don't run rest of the function
            return;
        }

        // Plugin activated for all blogs
        $blogs = $this->db->select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
        foreach($blogs as $blog_id)
        {
            switch_to_blog($blog_id);
            $this->install($blog_id);
        }

        switch_to_blog($current_blog_id);

        // Add WordPress flush rewrite rules in to do list
        LSD_RewriteRules::todo();
	}
    
    /**
     * Install the plugin on s certain blog
     * @param int $blog_id
     */
    public function install($blog_id = 1)
    {
        // Default Settings
        $settings = LSD_Options::defaults('settings');
        add_option('lsd_settings', $settings);

        // Default Social Networks
        $socials = LSD_Options::defaults('socials');
        add_option('lsd_socials', $socials);

        // Default Styles
        $styles = LSD_Options::defaults('styles');
        add_option('lsd_styles', $styles);

        // Default Page Details Options
        $details_page = LSD_Options::defaults('details_page');
        add_option('lsd_details_page', $details_page);

        // Default Page Details Pattern
        $details_page_pattern = LSD_Options::defaults('details_page_pattern');
        add_option('lsd_details_page_pattern', $details_page_pattern);

        // DB Update
        if($this->main->is_db_update_required())
        {
            $this->db_update();
        }

        // Generate personalized CSS File
        LSD_Personalize::generate();

        // Save Installation Time
        update_option('lsd_installation_time', time());
    }

    public function db_update()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'lsd_data';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE `$table_name` (
            `id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `latitude` decimal(10,8) NOT NULL DEFAULT '0.00000000',
            `longitude` decimal(11,8) NOT NULL DEFAULT '0.00000000',
            `point` point DEFAULT NULL,
            PRIMARY KEY (id),
            INDEX `latitude` (`latitude`,`longitude`)
        ) $charset_collate;";

        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        update_option('lsd_db_version', LSD_Base::DB_VERSION);
    }
    
    /**
     * Runs on plugin deactivation
     * @param boolean $network
     */
    public function deactivate($network = false)
	{
        /**
         * Refresh WordPress rewrite rules
         * We cannot use LSD_RewriteRules here because plugin is deactivated and it won't run
         */
        flush_rewrite_rules();
	}
    
    /**
     * Runs on plugin uninstallation
     */
    public static function uninstall()
	{
        // DB Class
        $db = new LSD_db();
        
        // Getting current blog
        $current_blog_id = get_current_blog_id();

        // Single WordPress Installation
        if(!function_exists('is_multisite') or (function_exists('is_multisite') and !is_multisite()))
        {
            self::purge($current_blog_id);

            /**
             * Refresh WordPress rewrite rules
             * We cannot use LSD_RewriteRules here because plugin is removed and it won't run
             */
            flush_rewrite_rules();
            
            // Don't run rest of the function
            return;
        }

        // WordPress is multisite so we should purge the plugin from al blogs
        $blogs = $db->select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
        foreach($blogs as $blog_id)
        {
            switch_to_blog($blog_id);
            self::purge($blog_id);
        }
        
        // Switch back to current blog
        switch_to_blog($current_blog_id);

        /**
         * Refresh WordPress rewrite rules
         * We cannot use LSD_RewriteRules here because plugin is removed and it won't run
         */
        flush_rewrite_rules();
	}
    
    /**
     * Remove Listdom from a blog
     * @param int $blog_id
     */
    public static function purge($blog_id = 1)
    {
        // Delete the data or not!
        $delete = apply_filters('lsd_purge_options', true);

        // Listdom Deleted
        if($delete)
        {
            delete_option('lsd_activation_redirect');
            delete_option('lsd_settings');
            delete_option('lsd_socials');
            delete_option('lsd_styles');
            delete_option('lsd_addons');
            delete_option('lsd_todo_flush');
            delete_option('lsd_installation_time');
            delete_option('lsd_purchase_code');
            delete_option('lsd_activation_id');
            delete_option('lsd_version');
        }
    }
}

endif;