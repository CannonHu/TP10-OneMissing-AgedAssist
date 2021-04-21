<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Dashboard_Terms')):

/**
 * Listdom Dashboard Terms Class.
 *
 * @class LSD_Dashboard_Terms
 * @version	1.0.0
 */
class LSD_Dashboard_Terms extends LSD_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function category($args)
    {
        $output = LSD_Dashboard_Terms::dropdown($args);

        // Apply Filters
        return apply_filters('lsd_dashboard_category_options', $output, $args);
	}

    public static function locations($args)
    {
        // Settings
        $settings = LSD_Options::settings();

        // Field Method
        $method = (isset($settings['submission_tax_'.$args['taxonomy'].'_method']) and trim($settings['submission_tax_'.$args['taxonomy'].'_method'])) ? $settings['submission_tax_'.$args['taxonomy'].'_method'] : 'checkboxes';

        if($method == 'dropdown')
        {
            $args['multiple'] = true;
            $args['selected'] = isset($args['post_id']) ? wp_get_post_terms($args['post_id'], $args['taxonomy'], array('fields' => 'ids')) : array();
			$args['class'] = 'lsd-select-multiple';

            $output = LSD_Dashboard_Terms::dropdown($args);
        }
        else $output = LSD_Dashboard_Terms::checkboxes($args);

        // Apply Filters
        return apply_filters('lsd_dashboard_locations_options', $output, $args);
    }

    public static function features($args)
    {
        // Settings
        $settings = LSD_Options::settings();

        // Field Method
        $method = (isset($settings['submission_tax_'.$args['taxonomy'].'_method']) and trim($settings['submission_tax_'.$args['taxonomy'].'_method'])) ? $settings['submission_tax_'.$args['taxonomy'].'_method'] : 'checkboxes';

        if($method == 'dropdown')
        {
            $args['multiple'] = true;
            $args['selected'] = isset($args['post_id']) ? wp_get_post_terms($args['post_id'], $args['taxonomy'], array('fields' => 'ids')) : array();
			$args['class'] = 'lsd-select-multiple';
			
            $output = LSD_Dashboard_Terms::dropdown($args);
        }
        else $output = LSD_Dashboard_Terms::checkboxes($args);

        // Apply Filters
        return apply_filters('lsd_dashboard_features_options', $output, $args);
    }

    public static function labels($args)
    {
        $output = LSD_Dashboard_Terms::checkboxes($args);

        // Apply Filters
        return apply_filters('lsd_dashboard_labels_options', $output, $args);
    }

    public static function checkboxes($args, $selecteds = NULL)
    {
        $a = $args;
        foreach(array('id', 'name', 'post_id', 'class') as $key) if(isset($a[$key])) unset($a[$key]);

        // Field Name
        $name = isset($args['name']) ? $args['name'] : '';
        $pre = isset($args['pre']) ? $args['pre'] : '-';

        // Current Values
        if(is_null($selecteds)) $selecteds = isset($args['post_id']) ? wp_get_post_terms($args['post_id'], $args['taxonomy'], array('fields' => 'ids')) : array();

        // Available Terms
        $terms = self::terms($a);

        $prefix = '';
        for($i = 0; $i < $args['level']; $i++) $prefix .= $pre;

        $output = '<ul '.($args['level'] > 0 ? 'class="lsd-children"' : '').'>';
        foreach($terms as $term)
        {
            $output .= '<li>';
            $output .= '<label class="selectit"><input value="'.esc_attr($term->term_id).'" type="checkbox" name="'.esc_attr($name).'[]" id="in-listdom-location-'.esc_attr($term->term_id).'" '.(in_array($term->term_id, $selecteds) ? 'checked="checked"' : '').'> '.esc_html(($prefix.(trim($prefix) ? ' ' : '').$term->name)).'</label>';

            $children = get_term_children($term->term_id, $args['taxonomy']);
            if(is_array($children) and count($children))
            {
                $a = $args;
                $a['parent'] = $term->term_id;
                $a['level'] = $a['level']+1;

                $output .= self::checkboxes($a, $selecteds);
            }

            $output .= '</li>';
        }

        $output .= '</ul>';
        return $output;
    }

    public static function dropdown($args = array())
    {
        $a = $args;
        foreach(array('id', 'name', 'selected', 'class') as $key) if(isset($a[$key])) unset($a[$key]);

        $name = isset($args['name']) ? $args['name'] : '';
        $id = isset($args['id']) ? $args['id'] : '';
        $class = isset($args['class']) ? $args['class'] : 'postform';
        $selected = isset($args['selected']) ? $args['selected'] : '';
        $multiple = isset($args['multiple']) ? (boolean) $args['multiple'] : false;
        $none = isset($args['none']) ? (boolean) $args['none'] : false;
        $none_label = isset($args['none_label']) ? $args['none_label'] : '-----';

        $output = '<select name="'.esc_attr($name).($multiple ? '[]' : '').'" id="'.esc_attr($id).'" class="'.esc_attr($class).'" '.($multiple ? 'multiple' : '').'>';
        if($none) $output .= '<option class="level-0" value="">'.$none_label.'</option>';

        $output .= self::options($a, 0, $selected, 0);
        $output .= '</select>';

        return $output;
    }

    public static function options($args, $parent = 0, $selected = NULL, $level = 0)
    {
        $args['parent'] = $parent;
        $terms = self::terms($args);

        $prefix = '';
        for($i = 0; $i < $level; $i++) $prefix .= '-';

        $output = '';
        foreach($terms as $term)
        {
            $output .= '<option class="level-'.esc_attr($level).'" value="'.esc_attr($term->term_id).'" '.(((is_array($selected) and in_array($term->term_id, $selected)) or (!is_array($selected) and $selected == $term->term_id)) ? 'selected="selected"' : '').'>'.esc_html(($prefix.(trim($prefix) ? ' ' : '').$term->name)).'</option>';

            $children = get_term_children($term->term_id, $args['taxonomy']);
            if(is_array($children) and count($children))
            {
                $output .= self::options($args, $term->term_id, $selected, $level+1);
            }
        }

        return $output;
    }

    public static function terms($args = array())
    {
        // Get Terms
        $terms = get_terms($args);

        // Do not filter (Used in package add / edit page in backend)
        if(isset($args['lsd_no_filter']) and $args['lsd_no_filter']) return $terms;

        // Apply Filters
        return apply_filters('lsd_dashboard_terms', $terms, $args);
    }
}

endif;