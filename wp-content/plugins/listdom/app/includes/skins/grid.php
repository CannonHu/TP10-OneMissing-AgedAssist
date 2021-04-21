<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Skins_Grid')):

/**
 * Listdom Skins Grid Class.
 *
 * @class LSD_Skins_Grid
 * @version	1.0.0
 */
class LSD_Skins_Grid extends LSD_Skins
{
    public $skin = 'grid';
    public $default_style = 'style1';

    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        add_action('wp_ajax_lsd_grid_load_more', array($this, 'filter'));
        add_action('wp_ajax_nopriv_lsd_grid_load_more', array($this, 'filter'));

        add_action('wp_ajax_lsd_grid_sort', array($this, 'filter'));
        add_action('wp_ajax_nopriv_lsd_grid_sort', array($this, 'filter'));
    }

    public function filter()
    {
        // Get attributes
        $atts = isset($_POST['atts']) ? $_POST['atts'] : array();

        // Sanitization
        array_walk_recursive($atts, 'sanitize_text_field');

        // Start the skin
        $this->start($atts);
        $this->after_start();

        // Generate the Query
        $this->query();

        // Apply Search Parameters
        $this->apply_search($_POST);

        // Fetch the listings
        $this->fetch();

        // Generate the output
        $output = $this->listings_html();

        $response = array('success'=>1, 'html'=>LSD_Kses::page($output), 'next_page'=>$this->next_page, 'count'=>count($this->listings), 'total'=>$this->found_listings, 'seed'=>(isset($this->atts['seed']) ? $this->atts['seed'] : NULL));
        $this->response($response);
    }
}

endif;