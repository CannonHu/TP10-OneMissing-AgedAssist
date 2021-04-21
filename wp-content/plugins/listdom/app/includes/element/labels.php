<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Element_Labels')):

/**
 * Listdom Labels Element Class.
 *
 * @class LSD_Element_Labels
 * @version	1.0.0
 */
class LSD_Element_Labels extends LSD_Element
{
    public $key = 'labels';
    public $label;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->label = esc_html__('Labels', 'listdom');
	}

	public function get($post_id = NULL)
    {
        if(is_null($post_id))
        {
            global $post;
            $post_id = $post->ID;
        }

        // Generate output
        ob_start();
        include lsd_template('elements/labels.php');
        return ob_get_clean();
    }

    public static function styles($label_id)
    {
        $color = get_term_meta($label_id, 'lsd_color', true);
        $text = LSD_Base::get_text_color($color);

        return 'style="background-color: '.esc_attr($color).'; color: '.esc_attr($text).';"';
    }
}

endif;