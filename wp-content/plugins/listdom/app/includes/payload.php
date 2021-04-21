<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Payload')):

/**
 * Listdom Payload Class.
 *
 * @class LSD_Payload
 * @version	1.0.0
 */
class LSD_Payload extends LSD_Base
{
    protected static $vars;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function set($key, $value)
    {
        self::$vars[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        return isset(self::$vars[$key]) ? self::$vars[$key] : NULL;
    }

    public static function remove($key)
    {
        if(isset(self::$vars[$key]))
        {
            unset(self::$vars[$key]);
            return true;
        }

        return false;
    }
}

endif;