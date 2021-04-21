<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API')):

/**
 * Listdom API Class.
 *
 * @class LSD_API
 * @version	1.0.0
 */
class LSD_API extends LSD_Base
{
    public $namespace = 'listdom/v1';
    public $version = '1';

    /**
     * @var LSD_db
     */
    protected $db;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        // DB Library
        $this->db = new LSD_db();
	}
    
    public function init()
    {
        add_action('init', array($this, 'language'), 20);

        $routes = new LSD_API_Routes();
        $routes->init();
    }

    public function language()
    {
        // Requested Language
        $locale = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : NULL);

        if(!$locale) return;

        // Switch the Language
        LSD_i18n::set($locale);
    }
}

endif;