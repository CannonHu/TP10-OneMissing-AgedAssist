<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Addons')):

/**
 * Listdom Addons Class.
 *
 * @class LSD_Addons
 * @version	1.0.0
 */
class LSD_Addons extends LSD_Base
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
        // Listdom Elementor Addon
        $Elementor = new LSD_Addons_Elementor();
        $Elementor->init();

        // Listdom Block Editor Addon
        $BE = new LSD_Addons_BE();
        $BE->init();

        // Listdom VC Addon
        $VC = new LSD_Addons_VC();
        $VC->init();

        // Listdom Divi Addon
        $Divi = new LSD_Addons_Divi();
        $Divi->init();

        // Listdom KC Addon
        $KC = new LSD_Addons_KC();
        $KC->init();
    }
}

endif;