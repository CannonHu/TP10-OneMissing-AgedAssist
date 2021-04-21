<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Shortcodes_Listdom')):

/**
 * Listdom Main Shortcode Class.
 *
 * @class LSD_Shortcodes_Listdom
 * @version	1.0.0
 */
class LSD_Shortcodes_Listdom extends LSD_Shortcodes
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
        add_shortcode('listdom', array($this, 'output'));
    }

    public function output($atts = array(), $override = array())
    {
        $shortcode_id = isset($atts['id']) ? (int) $atts['id'] : 0;

        $atts = wp_parse_args($override, apply_filters('lsd_shortcode_atts', $this->parse($shortcode_id, $atts)));
        $skin = (isset($atts['lsd_display']) and isset($atts['lsd_display']['skin'])) ? $atts['lsd_display']['skin'] : $this->get_default_skin();

        return $this->skin($skin, $atts);
    }

    public function skin($skin, $atts)
    {
        // Get Skin Object
        $SKO = $this->SKO($skin);

        // Start the skin
        $SKO->start($atts);
        $SKO->after_start();

        // Generate the Query
        $SKO->query();

        // Fetch the listings
        $SKO->fetch();

        return $SKO->output();
    }

    public function widget($shortcode_id)
    {
        $atts = apply_filters('lsd_shortcode_atts', $this->parse($shortcode_id, array('id' => $shortcode_id, 'html_class'=>'lsd-widget lsd-shortcode-widget', 'widget'=>true)));
        $skin = (isset($atts['lsd_display']) and isset($atts['lsd_display']['skin'])) ? $atts['lsd_display']['skin'] : $this->get_default_skin();

        return $this->skin($skin, $atts);
    }

    public function embed($shortcode_id)
    {
        $atts = apply_filters('lsd_shortcode_atts', $this->parse($shortcode_id, array('id' => $shortcode_id, 'html_class'=>'lsd-embed lsd-shortcode-embed', 'embed'=>true)));
        $skin = (isset($atts['lsd_display']) and isset($atts['lsd_display']['skin'])) ? $atts['lsd_display']['skin'] : $this->get_default_skin();

        return $this->skin($skin, $atts);
    }

    public function SKO($skin)
    {
        if($skin == 'singlemap') $SKO = new LSD_Skins_Singlemap();
        elseif($skin == 'list') $SKO = new LSD_Skins_List();
        elseif($skin == 'grid') $SKO = new LSD_Skins_Grid();
        elseif($skin == 'listgrid') $SKO = new LSD_Skins_Listgrid();
        elseif($skin == 'halfmap') $SKO = new LSD_Skins_Halfmap();
        elseif($skin == 'table') $SKO = new LSD_Skins_Table();
        elseif($skin == 'cover') $SKO = new LSD_Skins_Cover();
        elseif($skin == 'carousel') $SKO = new LSD_Skins_Carousel();
        elseif($skin == 'slider') $SKO = new LSD_Skins_Slider();
        elseif($skin == 'masonry') $SKO = new LSD_Skins_Masonry();
        else $SKO = new LSD_Skins_Singlemap();

        return $SKO;
    }
}

endif;