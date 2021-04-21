<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Widgets_Shortcode')):

/**
 * Listdom Widgets Shortcode Class.
 *
 * @class LSD_Widgets_Shortcode
 * @version	1.0.0
 */
class LSD_Widgets_Shortcode extends WP_Widget
{
    public $LSD;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct('LSD_Widgets_Shortcode', esc_html__('(Listdom) Shortcode', 'listdom'), array('description'=>esc_html__('Put desired Listdom shortcodes into a sidebar/widget area.', 'listdom')));

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

        $shortcode_id = isset($instance['shortcode_id']) ? (int) $instance['shortcode_id'] : 0;

        // Print the skin output
        $shortcode = new LSD_Shortcodes_Listdom();
        echo LSD_Kses::page($shortcode->widget($shortcode_id));

        // After Widget
        echo (isset($args['after_widget']) ? $args['after_widget'] : '');
	}

    public function form($instance)
    {
        $shortcodes = get_posts(array('post_type'=>LSD_Base::PTYPE_SHORTCODE, 'posts_per_page'=>'-1', 'meta_query'=>array(array('key'=>'lsd_skin', 'value'=>array('singlemap', 'grid', 'list', 'table'), 'compare'=>'IN'))));

        if(count($shortcodes))
        {
            echo '<p class="lsd-widget-row">
                <label for="'.$this->get_field_id('title').'">'.esc_html__('Title', 'listdom').'</label>
                <input class="widefat" type="text" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.(isset($instance['title']) ? esc_attr($instance['title']) : '').'" />
            </p>';

            echo '<p class="lsd-widget-row">
                <label for="'.$this->get_field_id('shortcode_id').'">'.esc_html__('Shortcode', 'listdom').'</label>
                <select class="widefat" name="'.$this->get_field_name('shortcode_id').'" id="'.$this->get_field_id('shortcode_id').'"><option value="">-----</option>';

                foreach($shortcodes as $shortcode) echo '<option value="'.$shortcode->ID.'"'.((isset($instance['shortcode_id']) and $instance['shortcode_id'] == $shortcode->ID) ? ' selected="selected"' : '').'>'.$shortcode->post_title.'</option>';

                echo '</select>
                <p class="description lsd-p-0">'.esc_html__('Your sidebar / widget area should be wide enough to show the shortcodes correctly. If the sidebar size is not wide then you should not use this widget.', 'listdom').'</p>
            </p>';
        }
        else
        {
            echo '<p class="lsd-widget-row lsd-no-shortcodes"><a href="'.admin_url('edit.php?post_type='.LSD_Base::PTYPE_SHORTCODE).'">'.esc_html__('Please create some shortcodes first!').'</a></p>';
        }
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = isset($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['shortcode_id'] = isset($new_instance['shortcode_id']) ? (int) $new_instance['shortcode_id'] : 0;

        return $instance;
    }
}

endif;