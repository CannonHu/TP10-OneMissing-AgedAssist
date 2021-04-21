<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Addons_KC')):

/**
 * Listdom Addon KC Class.
 *
 * @class LSD_Addons_KC
 * @version	1.0.0
 */
class LSD_Addons_KC extends LSD_Addons
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
        // King Composer is not installed
        if(!function_exists('kc_add_map')) return false;

        add_action('init', array($this, 'listdom'));
        return true;
    }

    public function listdom()
    {
        $shortcodes = get_posts(array('post_type'=>LSD_Base::PTYPE_SHORTCODE, 'posts_per_page'=>'-1', 'meta_query'=>array(array('key'=>'lsd_skin', 'value'=>array('singlemap', 'grid', 'list', 'listgrid'), 'compare'=>'IN'))));

        $options = array();
        foreach($shortcodes as $shortcode) $options[$shortcode->post_title] = $shortcode->ID;

        kc_add_map(array
        (
            'listdom'=>array(
                'name'=>esc_html__('Listdom', 'listdom'),
                'category'=>esc_html__('Content', 'listdom'),
                'params'=>array(
                    'General'=>array(
                        array(
                            'name'=>'id',
                            'label'=>esc_html__('Shortcode', 'listdom'),
                            'type'=>'select',
                            'options'=>$options,
                            'description'=>esc_html__('Select one of predefined shortcodes.', 'listdom'),
                        ),
                    ),
                )
            ),
        ));
    }
}

endif;