<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Shortcodes_Taxonomy')):

/**
 * Listdom LSD Taxonomy Shortcode Class.
 *
 * @class LSD_Shortcodes_Taxonomy
 * @version	1.0.0
 */
class LSD_Shortcodes_Taxonomy extends LSD_Shortcodes
{
    protected $TX;
    protected $id;
    protected $atts = array();
    protected $terms = array();
    protected $default_style = 'clean';
    protected $valid_styles = array();
    protected $columns = 1;
    protected $hierarchical = false;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        // Unique ID
        $this->id = LSD_id::get(mt_rand(100, 999));
	}

    public function init()
    {
    }

    public function output($atts = array())
    {
        // If taxonomy is invalid
        if(!trim($this->TX)) return $this->alert(esc_html__('Taxonomy is invalid!', 'listdom'), 'warning');

        // Shortcode attributes
        $this->atts = is_array($atts) ? $atts : array();

        // Shortcode Style
        $style = isset($this->atts['style']) ? $this->atts['style'] : $this->default_style;

        // The style is invalid!
        if(!in_array($style, $this->valid_styles))
        {
            $valid_styles = '';
            foreach($this->valid_styles as $valid_style) $valid_styles .= '<strong>'.esc_html($valid_style).'</strong>, ';

            return $this->alert(sprintf(esc_html__('Style is invalid! Valid styles are: %s', 'listdom'), trim($valid_styles, ', ')), 'warning');
        }

        // Hierarchical
        $this->hierarchical = (isset($this->atts['hierarchical']) and $this->atts['hierarchical']) ? true : false;

        // Parent
        $parent = ($this->hierarchical ? ((isset($this->atts['parent']) and trim($this->atts['parent']) != '') ? $this->atts['parent'] : 0) : NULL);

        // Terms
        $this->terms = $this->get_terms($parent);

        // Output
        switch($style)
        {
            case 'image';
                $output = $this->image();
                break;

            case 'simple';
                $output = $this->simple();
                break;

            case 'carousel';
                $output = $this->carousel();
                break;

            default:
                $output = $this->clean();
                break;
        }

        // Shortcode Output
        return $output;
    }

    public function get_terms($parent = NULL)
    {
        $args = array();

        // Hide Empty
        $args['hide_empty'] = isset($this->atts['hide_empty']) ? $this->atts['hide_empty'] : false;

        // Filter by Parent
        if(is_null($parent) and isset($this->atts['parent']) and trim($this->atts['parent']) != '')
        {
            $parent = $this->atts['parent'];

            /**
             * Client inserted term name instead of term ID
             * So we should convert it to ID first
             **/
            if(!is_numeric($parent))
            {
                $term = get_term_by('name', $parent, $this->TX);
                if(isset($term->term_id)) $parent = $term->term_id;
            }
        }

        if(!is_null($parent)) $args['parent'] = $parent;

        // Term IDs
        if(isset($this->atts['ids']) and trim($this->atts['ids'])) $args['object_ids'] = explode(',', $this->atts['ids']);

        // Filter by Keyword
        if(isset($this->atts['search']) and trim($this->atts['search'])) $args['search'] = $this->atts['search'];

        // Order Options
        $args['orderby'] = (isset($this->atts['orderby']) ? $this->atts['orderby'] : 'name');
        $args['order'] = (isset($this->atts['order']) ? $this->atts['order'] : 'ASC');

        // Limit Options
        $args['number'] = (isset($this->atts['limit']) ? $this->atts['limit'] : 8);

        // Get the terms
        return get_terms($this->TX, $args);
    }

    public function image()
    {
        // Generate output
        ob_start();
        include lsd_template('taxonomy-shortcodes/image.php');
        return LSD_Kses::element(ob_get_clean());
    }

    public function simple()
    {
        // Generate output
        ob_start();
        include lsd_template('taxonomy-shortcodes/simple.php');
        return LSD_Kses::element(ob_get_clean());
    }

    public function clean()
    {
        // Generate output
        ob_start();
        include lsd_template('taxonomy-shortcodes/clean.php');
        return LSD_Kses::element(ob_get_clean());
    }

    public function carousel()
    {
        // Generate output
        ob_start();
        include lsd_template('taxonomy-shortcodes/carousel.php');
        return LSD_Kses::element(ob_get_clean());
    }
}

endif;