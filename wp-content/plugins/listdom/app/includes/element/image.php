<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Element_Image')):

/**
 * Listdom Image Element Class.
 *
 * @class LSD_Element_Image
 * @version	1.0.0
 */
class LSD_Element_Image extends LSD_Element
{
    public $key = 'image';
    public $label;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->label = esc_html__('Featured Image', 'listdom');
	}

    public function get($size, $post_id = NULL)
    {
        if(is_null($post_id))
        {
            global $post;
            $post_id = $post->ID;
        }

        // Generate output
        ob_start();
        include lsd_template('elements/featured-image.php');
        return ob_get_clean();
    }

    public function cover($size = array(350, 220), $post_id = NULL)
    {
        if(is_null($post_id))
        {
            global $post;
            $post_id = $post->ID;
        }

        // Generate output
        ob_start();
        include lsd_template('elements/cover-image.php');
        return ob_get_clean();
    }
}

endif;