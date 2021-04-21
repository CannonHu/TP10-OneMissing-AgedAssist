<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Search_Helper')):

/**
 * Listdom Search Helper Class.
 *
 * @class LSD_Search_Helper
 * @version	1.0.0
 */
class LSD_Search_Helper extends LSD_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function get_type_by_key($key)
    {
        if(in_array($key, $this->taxonomies())) return 'taxonomy';
        elseif(strpos($key, 'att') !== false)
        {
            $ex = explode('-', $key);
            return get_term_meta($ex[1], 'lsd_field_type', true);
        }
        elseif($key == 'price') return 'price';
        elseif($key == 'class') return 'class';
        elseif($key == 'address') return 'address';
        elseif($key == 'period') return 'period';
        elseif(in_array($key, array('adults', 'children'))) return 'numeric';
        else return 'textsearch';
    }

    public function get_terms($filter, $numeric = false)
    {
        $key = isset($filter['key']) ? $filter['key'] : NULL;

        if(in_array($key, $this->taxonomies()))
        {
            $results = get_terms(array(
                'taxonomy' => $key,
                'hide_empty' => (isset($filter['hide_empty']) ? $filter['hide_empty'] : 0),
                'orderby' => 'name',
                'order' => 'ASC',
            ));

            $terms = array();
            foreach($results as $result) $terms[$result->term_id] = $result->name;

            return $terms;
        }
        elseif(strpos($key, 'att') !== false)
        {
            $ex = explode('-', $key);
            $hide_empty = (isset($filter['hide_empty']) ? $filter['hide_empty'] : 1);

            if($hide_empty)
            {
                $order = "`meta_value`";
                if($numeric) $order = "CAST(`meta_value` as unsigned)";

                $db = new LSD_db();
                $results = $db->select("SELECT `meta_value` FROM `#__postmeta` WHERE `meta_key`='lsd_attribute_".esc_sql($ex[1])."' AND `meta_value`!='' GROUP BY `meta_value` ORDER BY ".$order." ASC", 'loadColumn');
            }
            else
            {
                $values_str = get_term_meta($ex[1], 'lsd_values', true);
                $results = explode(',', trim($values_str, ', '));
            }

            $terms = array();
            foreach($results as $result) $terms[$result] = $result;

            return $terms;
        }
        else return array();
    }

    public function get_term_id($taxonomy, $name)
    {
        $names = explode(',', $name);
        $ids = '';

        foreach($names as $name)
        {
            $term = get_term_by('name', trim($name), $taxonomy);
            $ids .= (isset($term->term_id) ? $term->term_id.',' : '');
        }

        $ids = trim($ids, ', ');
        return ((strpos($ids, ',') === false) ? (int) $ids : $ids);
    }

    public function column($count, $buttons = false)
    {
        if($count <= 1)
        {
            $field_column = $buttons ? 'lsd-col-10' : 'lsd-col-12';
            $button_column = 'lsd-col-2';
        }
        elseif($count == 2)
        {
            $field_column = $buttons ? 'lsd-col-5' : 'lsd-col-6';
            $button_column = 'lsd-col-2';
        }
        elseif($count == 3)
        {
            $field_column = $buttons ? 'lsd-col-3' : 'lsd-col-4';
            $button_column = 'lsd-col-3';
        }
        elseif($count == 4)
        {
            $field_column = $buttons ? 'lsd-col-2' : 'lsd-col-3';
            $button_column = 'lsd-col-4';
        }
        else
        {
            $field_column = 'lsd-col-2';
            $button_column = 'lsd-col-2';
        }

        return array($field_column, $button_column);
    }

    public function dropdown($filter, $args = array())
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;

        $id = isset($args['id']) ? $args['id'] : NULL;
        $name = isset($args['name']) ? $args['name'] : 'sf-'.$key;
        $current = isset($args['current']) ? $args['current'] : NULL;

        $output = '<select class="'.esc_attr($key).'" name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'">';
        $output .= '<option value="">'.esc_html__($placeholder, 'listdom').'</option>';
        $output .= $this->dropdown_options($filter, 0, $current, 0);
        $output .= '</select>';

        return $output;
    }

    public function dropdown_options($filter, $parent = 0, $current = NULL, $level = 0)
    {
        $key = isset($filter['key']) ? $filter['key'] : NULL;

        $all_terms = isset($filter['all_terms']) ? $filter['all_terms'] : 1;
        $predefined_terms = (isset($filter['terms']) and is_array($filter['terms'])) ? $filter['terms'] : array();

        $terms = get_terms(array(
            'taxonomy' => $key,
            'hide_empty' => (isset($filter['hide_empty']) ? $filter['hide_empty'] : NULL),
            'parent' => $parent,
            'orderby' => 'name',
            'order' => 'ASC',
        ));

        $prefix = '';
        for($i = 0; $i < $level; $i++) $prefix .= '-';

        $output = '';
        foreach($terms as $term)
        {
            // Term is not in the predefined terms
            if(!$all_terms and count($predefined_terms) and !isset($predefined_terms[$term->term_id])) continue;

            $output .= '<option class="level-'.esc_attr($level).'" value="'.esc_attr($term->term_id).'" '.($current == $term->term_id ? 'selected="selected"' : '').'>'.esc_html(($prefix.(trim($prefix) ? ' ' : '').$term->name)).'</option>';

            $children = get_term_children($term->term_id, $key);
            if(is_array($children) and count($children))
            {
                $output .= $this->dropdown_options($filter, $term->term_id, $current, $level+1);
            }
        }

        return $output;
    }

    public function hierarchical($filter, $args = array())
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $ph = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;
        $placeholders = explode(',', $ph);

        $all_terms = isset($filter['all_terms']) ? $filter['all_terms'] : 1;
        $predefined_terms = (isset($filter['terms']) and is_array($filter['terms'])) ? $filter['terms'] : array();

        $id = isset($args['id']) ? $args['id'] : NULL;
        $name = isset($args['name']) ? $args['name'] : 'sf-'.$key;
        $current = isset($args['current']) ? $args['current'] : NULL;
        $current_parents = ($current ? LSD_Taxonomies::parents(get_term($current)) : array());

        $terms = get_terms(array(
            'taxonomy' => $key,
            'hide_empty' => (isset($filter['hide_empty']) ? $filter['hide_empty'] : NULL),
            'orderby' => 'name',
            'order' => 'ASC',
        ));

        $hierarchy = array();
        foreach($terms as $term)
        {
            // Term is not in the predefined terms
            if(!$all_terms and count($predefined_terms) and !isset($predefined_terms[$term->term_id])) continue;

            $level = count(LSD_Taxonomies::parents($term))+1;

            if(!isset($hierarchy[$level])) $hierarchy[$level] = array();
            $hierarchy[$level][] = $term;
        }

        $max_levels = count($hierarchy);
        $output = '<div class="lsd-hierarchical-dropdowns" id="'.esc_attr($id).'_wrapper" data-for="'.esc_attr($key).'" data-id="'.esc_attr($id).'" data-max-levels="'.esc_attr($max_levels).'" data-name="'.esc_attr($name).'">';

        for($l = 1; $l <= $max_levels; $l++)
        {
            $level_terms = (isset($hierarchy[$l]) and is_array($hierarchy[$l])) ? $hierarchy[$l] : array();
            if(!count($level_terms)) continue;

            $placeholder = isset($placeholders[($l-1)]) ? $placeholders[($l-1)] : $placeholders[0];

            $output .= '<select class="'.esc_attr($key).'" name="'.esc_attr($name).'" id="'.esc_attr($id.'_'.$l).'" placeholder="'.esc_html__($placeholder, 'listdom').'" data-level="'.esc_attr($l).'">';
            $output .= '<option value="">'.esc_html__($placeholder, 'listdom').'</option>';

            foreach($level_terms as $level_term) $output .= '<option class="lsd-option lsd-parent-'.esc_attr($level_term->parent).'" value="'.esc_attr($level_term->term_id).'" '.(($current == $level_term->term_id or in_array($level_term->term_id, $current_parents)) ? 'selected="selected"' : '').'>'.esc_html($level_term->name).'</option>';
            $output .= '</select>';
        }

        $output .= '</div>';
        return $output;
    }
}

endif;