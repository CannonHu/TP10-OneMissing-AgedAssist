<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Shortcodes_Terms')):

/**
 * Listdom LSD Terms Shortcode Class.
 *
 * @class LSD_Shortcodes_Terms
 * @version	1.0.0
 */
class LSD_Shortcodes_Terms extends LSD_Shortcodes
{
    protected $atts = array();

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function init()
    {
        add_shortcode('listdom_terms', array($this, 'output'));
    }

    public function output($atts = array())
    {
        // Set the Attributes
        $this->atts = $atts;

        // Parameters
        $style = (isset($this->atts['dropdown']) and $this->atts['dropdown']) ? 'dropdown' : 'list';
        $hierarchical = (isset($this->atts['hierarchical']) and $this->atts['hierarchical']) ? true : false;
        $show_count = (isset($this->atts['show_count']) and $this->atts['show_count']) ? true : false;
        $taxonomy = (isset($this->atts['taxonomy']) and in_array($this->atts['taxonomy'], $this->taxonomies())) ? $this->atts['taxonomy'] : LSD_Main::TAX_CATEGORY;
        $id = LSD_id::get((isset($this->atts['id']) ? $this->atts['id'] : mt_rand(100, 999)));

        // Generate output
        ob_start();

        switch($style)
        {
            case 'dropdown':

                echo '<form action="'.esc_url(home_url()).'" method="get" class="lsd-terms-dropdown">';

                wp_dropdown_categories(array(
                    'show_option_none' => esc_html__('Select Category', 'listdom'),
                    'id' => $id,
                    'name' => $taxonomy,
                    'orderby' => 'name',
                    'hierarchical' => $hierarchical,
                    'show_count' => $show_count,
                    'taxonomy' => $taxonomy
                ));

                echo '</form>';

                break;
            default:

                echo '<ul>';

                wp_list_categories(array(
                    'orderby' => 'name',
                    'title_li' => '',
                    'hierarchical' => $hierarchical,
                    'show_count' => $show_count,
                    'taxonomy' => $taxonomy
                ));

                echo '</ul>';
        }

        return LSD_Kses::form(ob_get_clean());
    }
}

endif;