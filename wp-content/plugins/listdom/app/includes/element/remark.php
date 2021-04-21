<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Element_Remark')):

/**
 * Listdom Remark Element Class.
 *
 * @class LSD_Element_Remark
 * @version	1.0.0
 */
class LSD_Element_Remark extends LSD_Element
{
    public $key = 'remark';
    public $label;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->label = esc_html__('Remark / Owner Message', 'listdom');
	}

    public function get($post_id)
    {
        if(is_null($post_id))
        {
            global $post;
            $post_id = $post->ID;
        }

        return get_post_meta($post_id, 'lsd_remark', true);
    }
}

endif;