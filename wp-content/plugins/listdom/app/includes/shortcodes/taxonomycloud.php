<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Shortcodes_TaxonomyCloud')):

/**
 * Listdom LSD Taxonomy Cloud Shortcode Class.
 *
 * @class LSD_Shortcodes_TaxonomyCloud
 * @version	1.0.0
 */
class LSD_Shortcodes_TaxonomyCloud extends LSD_Shortcodes
{
    protected $atts = array();
    protected $terms = array();

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function init()
    {
        add_shortcode('listdom_cloud', array($this, 'output'));
    }

    public function output($atts = array())
    {
        // Set the Attributes
        $this->atts = $atts;

        // Terms
        $this->terms = $this->get_terms();

        // Generate output
        ob_start();
        include lsd_template('taxonomy-shortcodes/cloud.php');
        return LSD_Kses::element(ob_get_clean());
    }

    public function get_terms()
    {
        // Taxonomy
        $TX = isset($this->atts['taxonomy']) ? $this->atts['taxonomy'] : LSD_Base::TAX_TAG;

        $args = array();

        // Hide Empty
        $args['hide_empty'] = isset($this->atts['hide_empty']) ? $this->atts['hide_empty'] : false;

        // Filter by Parent
        if(isset($this->atts['parent']) and trim($this->atts['parent']))
        {
            $parent = $this->atts['parent'];

            /**
             * Client inserted term name instead of term ID
             * So we should convert it to ID first
             **/
            if(!is_numeric($parent))
            {
                $term = get_term_by('name', $parent, $TX);
                if(isset($term->term_id)) $parent = $term->term_id;
            }

            $args['parent'] = $parent;
        }

        // Term IDs
        if(isset($this->atts['ids']) and trim($this->atts['ids'])) $args['object_ids'] = explode(',', $this->atts['ids']);

        // Filter by Keyword
        if(isset($this->atts['search']) and trim($this->atts['search'])) $args['search'] = $this->atts['search'];

        // Order Options
        $args['orderby'] = (isset($this->atts['orderby']) ? $this->atts['orderby'] : 'name');

        if($args['orderby'] == 'name') $args['order'] = 'ASC';
        else $args['order'] = 'DESC';

        // Limit Options
        $args['number'] = (isset($this->atts['limit']) ? $this->atts['limit'] : 8);

        // Get the terms
        return get_terms($TX, $args);
    }
}

endif;