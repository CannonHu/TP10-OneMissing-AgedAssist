<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Widgets_Alllistings')):

/**
 * Listdom Widgets All Listings Class.
 *
 * @class LSD_Widgets_Alllistings
 * @version	1.0.0
 */
class LSD_Widgets_Alllistings extends WP_Widget
{
    public $LSD;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct('LSD_Widgets_Alllistings', esc_html__('(Listdom) All Listings', 'listdom'), array('description'=>esc_html__('Show all of your listings in a Map widget. If you like to filter listings, you should use Listdom Shortcode Widget.', 'listdom')));

        // Listdom Object
        $this->LSD = new LSD_Widgets();
	}

    public function widget($args, $instance)
    {
        // Before Widget
        echo (isset($args['before_widget']) ? $args['before_widget'] : '');

        // Print the widget title
        if(!empty($instance['title']))
        {
            echo (isset($args['before_title']) ? $args['before_title'] : '').apply_filters('widget_title', $instance['title']).(isset($args['after_title']) ? $args['after_title'] : '');
        }

        $limit = isset($instance['limit']) ? $instance['limit'] : 300;
        $map_provider = isset($instance['map_provider']) ? $instance['map_provider'] : 'leaflet';
        $clustering = isset($instance['clustering']) ? $instance['clustering'] : true;
        $style = isset($instance['style']) ? $instance['style'] : '';

        // Print the skin output
        $shortcode = new LSD_Shortcodes_Listdom();
        echo LSD_Kses::page($shortcode->skin('singlemap', array
        (
            'lsd_display'=>array(
                'singlemap'=>array(
                    'map_provider'=>$map_provider,
                    'limit'=>$limit,
                    'style'=>$style,
                    'clustering'=>$clustering
                )
            ),
            'lsd_mapcontrols'=>array(
                'zoom'=>'RIGHT_BOTTOM',
                'maptype'=>'TOP_LEFT',
                'streetview'=>'RIGHT_BOTTOM',
                'draw'=>'0',
                'gps'=>'0',
                'scale'=>'0',
                'fullscreen'=>'1',
            ),
            'html_class'=>'lsd-widget lsd-alllistings-widget',
            'widget'=>true
        )));

        // After Widget
        echo (isset($args['after_widget']) ? $args['after_widget'] : '');
	}

    public function form($instance)
    {
        $provider = (isset($instance['map_provider']) ? $instance['map_provider'] : 'leaflet');

        echo '<div id="'.$this->get_field_id('lsd_wrapper').'">';

        echo '<p class="lsd-widget-row">
            <label for="'.$this->get_field_id('title').'">'.esc_html__('Title', 'listdom').'</label>
            <input class="widefat" type="text" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.(isset($instance['title']) ? esc_attr($instance['title']) : '').'" />
        </p>';

        echo '<p class="lsd-widget-row">
            <label for="'.$this->get_field_id('map_provider').'">'.esc_html__('Map Provider', 'listdom').'</label>
            '.LSD_Form::providers(array(
                'id' => $this->get_field_id('map_provider'),
                'name' => $this->get_field_name('map_provider'),
                'class' => 'widefat lsd-map-provider-toggle',
                'value' => $provider,
                'attributes' => array(
                    'data-parent' => '#'.$this->get_field_id('lsd_wrapper')
                )
            )).'
        </p>';

        echo '<p class="lsd-widget-row lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
            <label for="'.$this->get_field_id('style').'">'.esc_html__('Style', 'listdom').'</label>
            '.LSD_Form::mapstyle(array(
                'id' => $this->get_field_id('style'),
                'name' => $this->get_field_name('style'),
                'class' => 'widefat',
                'value' => (isset($instance['style']) ? $instance['style'] : '')
            )).'
        </p>';

        echo '<p class="lsd-widget-row lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
            <label for="'.$this->get_field_id('clustering').'">'.esc_html__('Clustering', 'listdom').'</label>
            <select class="widefat" id="'.$this->get_field_id('clustering').'" name="'.$this->get_field_name('clustering').'">
                <option value="1" '.((isset($instance['clustering']) and $instance['clustering'] == '1') ? 'selected="selected"' : '').'>'.esc_html__('Enabled', 'listdom').'</option>
                <option value="0" '.((isset($instance['clustering']) and $instance['clustering'] == '0') ? 'selected="selected"' : '').'>'.esc_html__('Disabled', 'listdom').'</option>
            </select>
        </p>';

        echo '<p class="lsd-widget-row">
            <label for="'.$this->get_field_id('limit').'">'.esc_html__('Limit', 'listdom').'</label>
            <input class="widefat" type="number" id="'.$this->get_field_id('limit').'" name="'.$this->get_field_name('limit').'" value="'.(isset($instance['limit']) ? esc_attr($instance['limit']) : '').'" />
        </p>';

        echo '</div>';
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = isset($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['map_provider'] = isset($new_instance['map_provider']) ? sanitize_text_field($new_instance['map_provider']) : 'leaflet';
        $instance['style'] = isset($new_instance['style']) ? sanitize_text_field($new_instance['style']) : '';
        $instance['clustering'] = isset($new_instance['clustering']) ? (int) $new_instance['clustering'] : 1;
        $instance['limit'] = (isset($new_instance['limit']) and is_numeric($new_instance['limit']) and $new_instance['limit'] > 0) ? $new_instance['limit'] : 300;

        return $instance;
    }
}

endif;