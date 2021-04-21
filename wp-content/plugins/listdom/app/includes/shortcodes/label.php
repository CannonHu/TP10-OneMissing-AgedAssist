<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Shortcodes_Label')):

/**
 * Listdom Listdom Label Shortcode Class.
 *
 * @class LSD_Shortcodes_Label
 * @version	1.0.0
 */
class LSD_Shortcodes_Label extends LSD_Shortcodes_Taxonomy
{
    // Taxonomy
    protected $TX = LSD_Base::TAX_LABEL;

    // Valid Styles
    protected $valid_styles = array('simple', 'clean');

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function init()
    {
        add_shortcode('listdom_label', array($this, 'output'));
	}
}

endif;