<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Shortcodes_Search')):

/**
 * Listdom Search Shortcode Class.
 *
 * @class LSD_Shortcodes_Search
 * @version	1.0.0
 */
class LSD_Shortcodes_Search extends LSD_Shortcodes
{
    protected $id;
    protected $atts;
    protected $filters;
    protected $form;
    protected $sf;
    protected $col_filter;
    protected $col_button;
    protected $more_options;
    protected $settings;

    /**
     * @var LSD_Search_Helper
     */
    protected $helper;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        $this->more_options = false;
        $this->helper = new LSD_Search_Helper();

        $this->settings = LSD_Options::settings();
	}

    public function init()
    {
        add_shortcode('listdom-search', array($this, 'output'));
    }

    public function output($atts = array())
    {
        $this->id = isset($atts['id']) ? $atts['id'] : 0;

        // Attributes
        $this->atts = apply_filters('lsd_search_atts', $this->parse($this->id, $atts));

        // Filters
        $this->filters = isset($this->atts['lsd_fields']) ? $this->atts['lsd_fields'] : array();

        // Form
        $this->form = isset($this->atts['lsd_form']) ? $this->atts['lsd_form'] : array();

        // Overwrite Form Options
        if(isset($this->atts['page']) and trim($this->atts['page'])) $this->form['page'] = $this->atts['page'];
        if(isset($this->atts['shortcode']) and trim($this->atts['shortcode'])) $this->form['shortcode'] = $this->atts['shortcode'];

        // Current Values
        $this->sf = $this->get_sf();

        // Search Form
        ob_start();
        include lsd_template('search/tpl.php');
        return ob_get_clean();
    }

    /**
     * @param $key
     * @param null $default
     * @return array|null|string
     */
    public function current($key, $default = NULL)
    {
        $value = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;

        if(is_array($value) or is_object($value)) array_walk_recursive($value, 'sanitize_text_field');
        else $value = sanitize_text_field($value);

        return $value;
    }

    public function row($row)
    {
        $type = (isset($row['type']) and trim($row['type'])) ? $row['type'] : 'row';
        $filters = (isset($row['filters']) and is_array($row['filters']) and count($row['filters'])) ? $row['filters'] : array();
        $buttons = (isset($row['buttons']) and $row['buttons']) ? true : false;

        $row = '';
        if($type == 'row')
        {
            // Columns
            list($this->col_filter, $this->col_button) = $this->helper->column(count($filters), $buttons);

            $row .= '<div class="lsd-search-row '.($this->more_options ? 'lsd-search-included-in-more' : '').'"><div class="lsd-row">';

            foreach($filters as $filter) $row .= $this->filter($filter);
            if($buttons) $row .= $this->buttons();

            $row .= '</div></div>';
        }
        else
        {
            $this->more_options = true;

            $row .= '<div class="lsd-search-row-more-options">';
            $row .= '<span class="lsd-search-more-options"> '.esc_html__('More Options', 'listdom').'<i class="lsd-icon fa fa-plus"></i></span>';
            $row .= '</div>';
        }

        return $row;
    }

    public function buttons()
    {
        $buttons = '<div class="lsd-search-buttons '.esc_attr($this->col_button).'">';

        // Shortcode Input
        if(isset($this->form['shortcode']) and trim($this->form['shortcode'])) $buttons .= '<input type="hidden" name="sf-shortcode" value="'.esc_attr($this->form['shortcode']).'">';

        // Submit Button
        $buttons .= '<div class="lsd-search-buttons-submit"><button type="submit" class="lsd-search-button">'.esc_html__('Search', 'listdom').'</button></div>';

        $buttons .= '</div>';
        return $buttons;
    }

    public function criteria()
    {
        // Search Criteria
        $sf = $this->get_sf();

        // Human Readable Criteria
        $categories = '';
        $labels = '';
        $locations = '';

        // Category
        if(isset($sf[LSD_Base::TAX_CATEGORY]) and $sf[LSD_Base::TAX_CATEGORY])
        {
            $names = LSD_Taxonomies::name($sf[LSD_Base::TAX_CATEGORY], LSD_Base::TAX_CATEGORY);
            $categories = (is_array($names) ? '<strong>'.implode('</strong>, <strong>', $names).'</strong>' : '<strong>'.$names.'</strong>');
        }

        // Label
        if(isset($sf[LSD_Base::TAX_LABEL]) and $sf[LSD_Base::TAX_LABEL])
        {
            $names = LSD_Taxonomies::name($sf[LSD_Base::TAX_LABEL], LSD_Base::TAX_LABEL);
            $labels = (is_array($names) ? '<strong>'.implode('</strong>, <strong>', $names).'</strong>' : '<strong>'.$names.'</strong>');
        }

        // Location
        if(isset($sf[LSD_Base::TAX_LOCATION]) and $sf[LSD_Base::TAX_LOCATION])
        {
            $names = LSD_Taxonomies::name($sf[LSD_Base::TAX_LOCATION], LSD_Base::TAX_LOCATION);
            $locations = (is_array($names) ? '<strong>'.implode('</strong>, <strong>', $names).'</strong>' : '<strong>'.$names.'</strong>');
        }

        $criteria = '';
        if(trim($categories)) $criteria .= $categories.', ';
        if(trim($labels)) $criteria .= $labels.', ';
        if(trim($locations)) $criteria .= $locations.', ';

        $HR = (trim($criteria) ? sprintf(esc_html__("Results %s %s", 'listdom'), '<i class="lsd-icon fas fa-caret-right"></i>', trim($criteria, ', ')) : '');

        return '<div class="lsd-search-criteria">
            <span>'.$HR.'</span>
        </div>';
    }

    public function filter($filter)
    {
        $output = '';

        $key = isset($filter['key']) ? $filter['key'] : NULL;
        if(!$key) return $output;

        $type = $this->helper->get_type_by_key($key);
        switch($type)
        {
            case 'textsearch':

                $output = $this->field_textsearch($filter);
                break;

            case 'taxonomy':

                $output = $this->field_taxonomy($filter);
                break;

            case 'text':
            case 'textarea':

                $output = $this->field_text($filter);
                break;

            case 'numeric':
            case 'number':

                $output = $this->field_number($filter);
                break;

            case 'dropdown':

                $output = $this->field_dropdown($filter);
                break;

            case 'price':

                $output = $this->field_price($filter);
                break;

            case 'class':

                $output = $this->field_class($filter);
                break;

            case 'address':

                $output = $this->field_address($filter);
                break;

            case 'period':

                $output = $this->field_period($filter);
                break;
        }

        return $output;
    }

    public function field_textsearch($filter)
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;

        $id = 'lsd_search_'.$this->id.'_'.$key;
        $name = 'sf-'.$key;

        $default = isset($filter['default_value']) ? $filter['default_value'] : '';
        $current = $this->current($name, $default);

        $output = '<div class="lsd-search-filter '.esc_attr($this->col_filter).'">';
        $output .= '<label for="'.esc_attr($id).'">'.esc_html__($title, 'listdom').'</label>';
        $output .= '<input type="text" name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" value="'.esc_attr($current).'">';
        $output .= '</div>';

        return $output;
    }

    public function field_taxonomy($filter)
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $method = isset($filter['method']) ? $filter['method'] : 'dropdown';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;

        $all_terms = isset($filter['all_terms']) ? $filter['all_terms'] : 1;
        $predefined_terms = (isset($filter['terms']) and is_array($filter['terms'])) ? $filter['terms'] : array();

        $id = 'lsd_search_'.$this->id.'_'.$key;
        $name = 'sf-'.$key;

        $default = isset($filter['default_value']) ? $filter['default_value'] : '';
        if(trim($default) and !is_numeric($default)) $default = $this->helper->get_term_id($key, $default);

        $current = $this->current($name, $default);

        $output = '<div class="lsd-search-filter '.esc_attr($this->col_filter).'">';
        $output .= '<label for="'.esc_attr($id).'">'.esc_html__($title, 'listdom').'</label>';

        if($method === 'dropdown')
        {
            $output .= $this->helper->dropdown($filter, array(
                'id' => $id,
                'name' => $name,
                'current' => $current,
            ));
        }
        elseif($method === 'dropdown-multiple')
        {
            $current = $this->current($name, explode(',', $default));

            $output .= '<select class="'.esc_attr($key).'" name="'.esc_attr($name).'[]" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" multiple>';

            $terms = $this->helper->get_terms($filter);
            foreach($terms as $key=>$term)
            {
                // Term is not in the predefined terms
                if(!$all_terms and count($predefined_terms) and !isset($predefined_terms[$key])) continue;

                $output .= '<option value="'.esc_attr($key).'" '.(in_array($key, $current) ? 'selected="selected"' : '').'>'.esc_html($term).'</option>';
            }

            $output .= '</select>';
        }
        elseif($method === 'text-input')
        {
            $output .= '<input class="'.esc_attr($key).'" type="text" name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" value="'.esc_attr($current).'">';
        }
        elseif($method === 'checkboxes')
        {
            $current = $this->current($name, explode(',', $default));

            $terms = $this->helper->get_terms($filter);
            foreach($terms as $key=>$term)
            {
                // Term is not in the predefined terms
                if(!$all_terms and count($predefined_terms) and !isset($predefined_terms[$key])) continue;

                $output .= '<label><input type="checkbox" class="'.esc_attr($key).'" name="'.esc_attr($name).'[]" value="'.esc_attr($key).'" '.(in_array($key, $current) ? 'checked="checked"' : '').'>'.esc_html($term).'</label>';
            }
        }
        elseif($method === 'radio')
        {
            $terms = $this->helper->get_terms($filter);
            foreach($terms as $key=>$term)
            {
                // Term is not in the predefined terms
                if(!$all_terms and count($predefined_terms) and !isset($predefined_terms[$key])) continue;

                $output .= '<label><input type="radio" class="'.esc_attr($key).'" name="'.esc_attr($name).'" value="'.esc_attr($key).'" '.($current == $key ? 'checked="checked"' : '').'>'.esc_html($term).'</label>';
            }
        }
        elseif($method === 'hierarchical' and $this->isPro())
        {
            $output .= $this->helper->hierarchical($filter, array(
                'id' => $id,
                'name' => $name,
                'current' => $current,
            ));
        }

        $output .= '</div>';

        return $output;
    }

    public function field_text($filter)
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;

        $id = 'lsd_search_'.$this->id.'_'.$key;
        $name = 'sf-'.$key.'-lk';

        $default = isset($filter['default_value']) ? $filter['default_value'] : '';
        $current = $this->current($name, $default);

        $output = '<div class="lsd-search-filter '.esc_attr($this->col_filter).'">';
        $output .= '<label for="'.esc_attr($id).'">'.esc_html__($title, 'listdom').'</label>';
        $output .= '<input type="text" name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" value="'.esc_attr($current).'">';
        $output .= '</div>';

        return $output;
    }

    public function field_number($filter)
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $method = isset($filter['method']) ? $filter['method'] : 'number-input';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;

        $id = 'lsd_search_'.$this->id.'_'.$key;
        $name = 'sf-'.$key.'-eq';

        $default = isset($filter['default_value']) ? $filter['default_value'] : '';
        $current = $this->current($name, $default);

        $output = '<div class="lsd-search-filter '.esc_attr($this->col_filter).'">';
        $output .= '<label for="'.esc_attr($id).'">'.esc_html__($title, 'listdom').'</label>';

        if($method === 'number-input')
        {
            $output .= '<input type="number" name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" value="'.esc_attr($current).'">';
        }
        elseif($method === 'dropdown')
        {
            $output .= '<select name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'">';

            $terms = $this->helper->get_terms($filter, true);
            foreach($terms as $key=>$term) $output .= '<option value="'.esc_attr($term).'" '.($current == $term ? 'selected="selected"' : '').'>'.esc_html($term).'</option>';

            $output .= '</select>';
        }
        elseif($method === 'dropdown-plus')
        {
            $min = isset($filter['min']) ? $filter['min'] : 0;
            $max = isset($filter['max']) ? $filter['max'] : 100;
            $increment = isset($filter['increment']) ? $filter['increment'] : 10;
            $th_separator = (isset($filter['th_separator']) and $filter['th_separator']) ? true : false;

            $name = 'sf-'.$key.'-grq';
            $current = $this->current($name, $default);

            $output .= '<select name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'">';

            $i = $min;
            while($i <= $max)
            {
                $decimals = (floor($i) == $i) ? 0 : 2;

                $output .= '<option value="'.esc_attr($i).'" '.(($current == (string) $i) ? 'selected="selected"' : '').'>'.($th_separator ? number_format($i, $decimals, '.', ',') : $i).'+</option>';
                $i += $increment;
            }

            $output .= '</select>';
        }

        $output .= '</div>';

        return $output;
    }

    public function field_dropdown($filter)
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $method = isset($filter['method']) ? $filter['method'] : 'dropdown';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;

        $id = 'lsd_search_'.$this->id.'_'.$key;
        $name = 'sf-'.$key.'-eq';

        $default = isset($filter['default_value']) ? $filter['default_value'] : '';
        $current = $this->current($name, $default);

        $output = '<div class="lsd-search-filter '.esc_attr($this->col_filter).'">';
        $output .= '<label for="'.esc_attr($id).'">'.esc_html__($title, 'listdom').'</label>';

        if($method === 'dropdown')
        {
            $output .= '<select name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'">';
            $output .= '<option value="">'.esc_html__($placeholder, 'listdom').'</option>';

            $terms = $this->helper->get_terms($filter, true);
            foreach($terms as $key=>$term) $output .= '<option value="'.esc_attr($term).'" '.($current == $term ? 'selected="selected"' : '').'>'.esc_html($term).'</option>';

            $output .= '</select>';
        }
        elseif($method === 'dropdown-multiple')
        {
            $name = 'sf-'.$key.'-in';
            $current = $this->current($name, explode(',', $default));

            $output .= '<select name="'.esc_attr($name).'[]" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" multiple>';

            $terms = $this->helper->get_terms($filter, true);
            foreach($terms as $key=>$term) $output .= '<option value="'.esc_attr($term).'" '.(in_array($term, $current) ? 'selected="selected"' : '').'>'.esc_html($term).'</option>';

            $output .= '</select>';
        }
        elseif($method === 'text-input')
        {
            $output .= '<input type="text" name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" value="'.esc_attr($current).'">';
        }
        elseif($method === 'checkboxes')
        {
            $name = 'sf-'.$key.'-in';
            $current = $this->current($name, explode(',', $default));

            $terms = $this->helper->get_terms($filter, true);
            foreach($terms as $key=>$term) $output .= '<label><input type="checkbox" name="'.esc_attr($name).'[]" value="'.esc_attr($term).'" '.(in_array($term, $current) ? 'checked="checked"' : '').'>'.esc_html($term).'</label>';
        }
        elseif($method === 'radio')
        {
            $current = $this->current($name, explode(',', $default));

            $terms = $this->helper->get_terms($filter, true);
            foreach($terms as $key=>$term) $output .= '<label><input type="radio" name="'.esc_attr($name).'" value="'.esc_attr($term).'" '.($term == $current ? 'checked="checked"' : '').'>'.esc_html($term).'</label>';
        }

        $output .= '</div>';

        return $output;
    }

    public function field_price($filter)
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $method = isset($filter['method']) ? $filter['method'] : 'dropdown-plus';
        $title = isset($filter['title']) ? $filter['title'] : '';

        $min_placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;
        $max_placeholder = (isset($filter['max_placeholder']) and trim($filter['max_placeholder'])) ? $filter['max_placeholder'] : $title;

        $id = 'lsd_search_'.$this->id.'_'.$key;

        $min_default = isset($filter['default_value']) ? $filter['default_value'] : '';
        $max_default = isset($filter['max_default_value']) ? $filter['max_default_value'] : '';

        $output = '<div class="lsd-search-filter '.esc_attr($this->col_filter).'">';
        $output .= '<label for="'.esc_attr($id).'">'.esc_html__($title, 'listdom').'</label>';

        if($method === 'dropdown-plus')
        {
            $min = isset($filter['min']) ? $filter['min'] : 0;
            $max = isset($filter['max']) ? $filter['max'] : 100;
            $increment = isset($filter['increment']) ? $filter['increment'] : 10;
            $th_separator = (isset($filter['th_separator']) and $filter['th_separator']) ? true : false;

            $name = 'sf-att-'.$key.'-grq';
            $current = $this->current($name, $min_default);

            $output .= '<select name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($min_placeholder, 'listdom').'">';

            $i = $min;
            while($i <= $max)
            {
                $decimals = (floor($i) == $i) ? 0 : 2;

                $output .= '<option value="'.esc_attr($i).'" '.(($current == (string) $i) ? 'selected="selected"' : '').'>'.($th_separator ? number_format($i, $decimals, '.', ',') : $i).'+</option>';
                $i += $increment;
            }

            $output .= '</select>';
        }
        elseif($method === 'mm-input')
        {
            $min_name = 'sf-att-'.$key.'-bt-min';
            $min_current = $this->current($min_name, $min_default);

            $max_name = 'sf-att-'.$key.'-bt-max';
            $max_current = $this->current($max_name, $max_default);

            $output .= '<div class="lsd-search-mm-input">';
            $output .= '<input type="text" name="'.esc_attr($min_name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($min_placeholder, 'listdom').'" value="'.esc_attr($min_current).'">';
            $output .= '<input type="text" name="'.esc_attr($max_name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($max_placeholder, 'listdom').'" value="'.esc_attr($max_current).'">';
            $output .= '</div>';
        }

        $output .= '</div>';

        return $output;
    }

    public function field_class($filter)
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $method = isset($filter['method']) ? $filter['method'] : 'dropdown';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;

        $id = 'lsd_search_'.$this->id.'_'.$key;

        $default = isset($filter['default_value']) ? $filter['default_value'] : '';
        if(!is_numeric($default)) $default = (int) substr_count($default, '$');

        $output = '<div class="lsd-search-filter '.esc_attr($this->col_filter).'">';
        $output .= '<label for="'.esc_attr($id).'">'.esc_html__($title, 'listdom').'</label>';

        if($method === 'dropdown')
        {
            $name = 'sf-att-'.$key.'-eq';
            $current = $this->current($name, $default);

            $output .= '<select name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'">';

            $output .= '<option value="">'.esc_html__('Any', 'listdom').'</option>';
            $output .= '<option value="1" '.(($current == 1) ? 'selected="selected"' : '').'>'.esc_html__('$', 'listdom').'</option>';
            $output .= '<option value="2" '.(($current == 2) ? 'selected="selected"' : '').'>'.esc_html__('$$', 'listdom').'</option>';
            $output .= '<option value="3" '.(($current == 3) ? 'selected="selected"' : '').'>'.esc_html__('$$$', 'listdom').'</option>';
            $output .= '<option value="4" '.(($current == 4) ? 'selected="selected"' : '').'>'.esc_html__('$$$$', 'listdom').'</option>';

            $output .= '</select>';
        }

        $output .= '</div>';
        return $output;
    }

    public function field_address($filter)
    {
        $key = isset($filter['key']) ? $filter['key'] : '';
        $method = isset($filter['method']) ? $filter['method'] : 'text-input';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;

        $id = 'lsd_search_'.$this->id.'_'.$key;
        $name = 'sf-att-'.$key.'-lk';

        $default = isset($filter['default_value']) ? $filter['default_value'] : '';
        $current = $this->current($name, $default);

        $output = '<div class="lsd-search-filter '.esc_attr($this->col_filter).'">';
        $output .= '<label for="'.esc_attr($id).'">'.esc_html__($title, 'listdom').'</label>';

        if($method === 'text-input')
        {
            $output .= '<input type="text" name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" value="'.esc_attr($current).'">';
        }
        elseif($method === 'radius')
        {
            $name = 'sf-circle-center';
            $current = $this->current($name, $default);

            // Radius
            $radius = isset($filter['radius']) ? $filter['radius'] : '';

            $output .= '<input type="text" name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" value="'.esc_attr($current).'">';
            $output .= '<input type="hidden" name="sf-circle-radius" value="'.esc_attr($radius).'">';
        }

        $output .= '</div>';

        return $output;
    }

    public function field_period($filter)
    {
        // Listdom Assets
        $assets = new LSD_Assets();

        // Date Range Picker
        $assets->moment();
        $assets->daterangepicker();

        $key = isset($filter['key']) ? $filter['key'] : '';
        $title = isset($filter['title']) ? $filter['title'] : '';
        $placeholder = (isset($filter['placeholder']) and trim($filter['placeholder'])) ? $filter['placeholder'] : $title;
        $format = (isset($this->settings['datepicker_format']) ? $this->settings['datepicker_format'] : 'yyyy-mm-dd');

        $id = 'lsd_search_'.$this->id.'_'.$key;
        $name = 'sf-'.$key;

        $default = isset($filter['default_value']) ? $filter['default_value'] : '';
        $current = $this->current($name, $default);

        $months = array();
        for($i = 0; $i <= 13; $i++) $months[] = LSD_Base::date(strtotime('+'.$i.' Months'), 'M Y');

        $output = '<div class="lsd-search-filter '.esc_attr($this->col_filter).'">';
        $output .= '<label for="'.esc_attr($id).'">'.esc_html__($title, 'listdom').'</label>';
        $output .= '<input type="text" class="lsd-date-range-picker" data-format="'.strtoupper(esc_attr($format)).'" data-periods="'.htmlspecialchars(json_encode($months), ENT_QUOTES, 'UTF-8').'" name="'.esc_attr($name).'" id="'.esc_attr($id).'" placeholder="'.esc_html__($placeholder, 'listdom').'" value="'.esc_attr($current).'">';
        $output .= '</div>';

        return $output;
    }
}

endif;