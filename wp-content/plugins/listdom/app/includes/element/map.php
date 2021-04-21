<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Element_Map')):

/**
 * Listdom Map Element Class.
 *
 * @class LSD_Element_Map
 * @version	1.0.0
 */
class LSD_Element_Map extends LSD_Element
{
    public $key = 'map';
    public $label;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        $this->label = esc_html__('Map', 'listdom');
	}

    public function get($post_id = NULL, $args = array())
    {
        if(is_null($post_id))
        {
            global $post;
            $post_id = $post->ID;
        }

        // Generate output
        return lsd_map(array($post_id), array
        (
            'provider'=>(isset($args['provider']) ? $args['provider'] : LSD_Map_Provider::def()),
            'clustering'=>false,
            'id'=>$post_id,
            'canvas_class'=>'lsd-map-canvas',
            'mapstyle'=>(isset($args['style']) ? $args['style'] : NULL),
            'gplaces'=>(isset($args['gplaces']) ? $args['gplaces'] : 0),
            'onclick'=>'infowindow', // Force to infowindow since we're on the details page already
            'mapcontrols'=>(isset($args['mapcontrols']) ? $args['mapcontrols'] : array()),
            'args'=>(isset($args['args']) ? $args['args'] : array()),
            'direction'=>apply_filters('lsd_map_direction', 0),
        ));
    }

    public function form($data = array())
    {
        // Positions Array
        $positions = $this->get_map_control_positions();
        $map_control_options = array_merge(array('0'=>esc_html__('Disabled', 'listdom')), $positions);

        // Third Party Fields
        ob_start();
        do_action('lsd_element_form_options', $this->key, $data);
        $additional = LSD_Kses::form(ob_get_clean());

        return '<div class="lsd-form-row">
            <div class="lsd-col-10 lsd-handler">
                <input type="hidden" name="lsd[elements]['.esc_attr($this->key).']" />
                <input type="hidden" name="lsd[elements]['.esc_attr($this->key).'][enabled]" value="'.esc_attr($data['enabled']).'" />
                '.$this->label.'
            </div>
            <div class="lsd-col-2 lsd-actions lsd-details-page-element-toggle-status" id="lsd_actions_'.esc_attr($this->key).'" data-key="'.esc_attr($this->key).'">
                <span class="lsd-toggle lsd-mr-2" data-for="#lsd_options_'.esc_attr($this->key).'" data-all=".lsd-element-options">
                    <i class="lsd-icon fa fa-cog fa-lg"></i>
                </span>
                <strong class="lsd-enabled '.($data['enabled'] ? '' : 'lsd-util-hide').'"><i class="lsd-icon fa fa-check"></i></strong>
                <strong class="lsd-disabled '.($data['enabled'] ? 'lsd-util-hide' : '').'"><i class="lsd-icon fa fa-minus-circle"></i></strong>
            </div>
        </div>
        <div class="lsd-element-options lsd-util-hide" id="lsd_options_'.esc_attr($this->key).'">
            <div class="lsd-form-row">
                <div class="lsd-col-2">
                    <label for="lsd_elements_'.esc_attr($this->key).'_show_title">'.esc_html__('Show Title', 'listdom').'</label>
                    <select name="lsd[elements]['.esc_attr($this->key).'][show_title]" id="lsd_elements_'.esc_attr($this->key).'_show_title">
                        <option value="1" '.((isset($data['show_title']) and $data['show_title'] == 1) ? 'selected="selected"' : '').'>'.esc_html__('Yes', 'listdom').'</option>
                        <option value="0" '.((isset($data['show_title']) and $data['show_title'] == 0) ? 'selected="selected"' : '').'>'.esc_html__('No', 'listdom').'</option>
                    </select>
                </div>
                <div class="lsd-col-2">
                    <label for="lsd_elements_'.esc_attr($this->key).'_map_provider">'.esc_html__('Map Provider', 'listdom').'</label>
                    '.LSD_Form::providers(array
                    (
                        'id'=>'lsd_elements_'.esc_attr($this->key).'_map_provider',
                        'name'=>'lsd[elements]['.esc_attr($this->key).'][map_provider]',
                        'value'=>(isset($data['map_provider']) ? $data['map_provider'] : LSD_Map_Provider::def()),
                        'class' => 'lsd-map-provider-toggle',
                        'attributes' => array(
                            'data-parent' => '#lsd_options_map'
                        )
                    )).'
                </div>
                <div class="lsd-col-2 lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
                    <label for="lsd_elements_'.esc_attr($this->key).'_style">'.esc_html__('Style', 'listdom').'</label>
                    '.LSD_Form::mapstyle(array
                    (
                        'id'=>'lsd_elements_'.esc_attr($this->key).'_style',
                        'name'=>'lsd[elements]['.esc_attr($this->key).'][style]',
                        'value'=>(isset($data['style']) ? $data['style'] : ''),
                    )).'
                </div>
                <div class="lsd-col-2 lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
                    <label for="lsd_elements_'.esc_attr($this->key).'_gplaces">'.esc_html__('Google Places', 'listdom').'</label>
                    '.LSD_Form::select(array
                    (
                        'id'=>'lsd_elements_'.esc_attr($this->key).'_gplaces',
                        'name'=>'lsd[elements]['.esc_attr($this->key).'][gplaces]',
                        'options'=>array('0'=>esc_html__('Disabled', 'listdom'), '1'=>esc_html__('Enabled', 'listdom')),
                        'value'=>(isset($data['gplaces']) ? $data['gplaces'] : 0),
                    )).'
                </div>
            </div>
            <div class="lsd-form-row lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
                <div class="lsd-col-2">
                    <label for="lsd_elements_'.esc_attr($this->key).'_control_zoom">'.esc_html__('Zoom Control', 'listdom').'</label>
                    '.LSD_Form::select(array
                    (
                        'id'=>'lsd_elements_'.esc_attr($this->key).'_control_zoom',
                        'name'=>'lsd[elements]['.esc_attr($this->key).'][control_zoom]',
                        'options'=>$map_control_options,
                        'value'=>(isset($data['control_zoom']) ? $data['control_zoom'] : 'RIGHT_BOTTOM'),
                    )).'
                </div>
                <div class="lsd-col-2">
                    <label for="lsd_elements_'.esc_attr($this->key).'_control_maptype">'.esc_html__('Map Type', 'listdom').'</label>
                    '.LSD_Form::select(array
                    (
                        'id'=>'lsd_elements_'.esc_attr($this->key).'_control_maptype',
                        'name'=>'lsd[elements]['.esc_attr($this->key).'][control_maptype]',
                        'options'=>$map_control_options,
                        'value'=>(isset($data['control_maptype']) ? $data['control_maptype'] : 'TOP_LEFT'),
                    )).'
                </div>
                <div class="lsd-col-2">
                    <label for="lsd_elements_'.esc_attr($this->key).'_control_streetview">'.esc_html__('Street View', 'listdom').'</label>
                    '.LSD_Form::select(array
                    (
                        'id'=>'lsd_elements_'.esc_attr($this->key).'_control_streetview',
                        'name'=>'lsd[elements]['.esc_attr($this->key).'][control_streetview]',
                        'options'=>$map_control_options,
                        'value'=>(isset($data['control_streetview']) ? $data['control_streetview'] : 'RIGHT_BOTTOM'),
                    )).'
                </div>
                <div class="lsd-col-2">
                    <label for="lsd_elements_'.esc_attr($this->key).'_control_scale">'.esc_html__('Scale Control', 'listdom').'</label>
                    '.LSD_Form::select(array
                    (
                        'id'=>'lsd_elements_'.esc_attr($this->key).'_control_scale',
                        'name'=>'lsd[elements]['.esc_attr($this->key).'][control_scale]',
                        'options'=>array('0'=>esc_html__('Disabled', 'listdom'), '1'=>esc_html__('Enabled', 'listdom')),
                        'value'=>(isset($data['control_scale']) ? $data['control_scale'] : '0'),
                    )).'
                </div>
                <div class="lsd-col-2">
                    <label for="lsd_elements_'.esc_attr($this->key).'_control_fullscreen">'.esc_html__('Fullscreen', 'listdom').'</label>
                    '.LSD_Form::select(array
                    (
                        'id'=>'lsd_elements_'.esc_attr($this->key).'_control_fullscreen',
                        'name'=>'lsd[elements]['.esc_attr($this->key).'][control_fullscreen]',
                        'options'=>array('0'=>esc_html__('Disabled', 'listdom'), '1'=>esc_html__('Enabled', 'listdom')),
                        'value'=>(isset($data['control_fullscreen']) ? $data['control_fullscreen'] : '1'),
                    )).'
                </div>
            </div>
            '.$additional.'
        </div>';
    }
}

endif;