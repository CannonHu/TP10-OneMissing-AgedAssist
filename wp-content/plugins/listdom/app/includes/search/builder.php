<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Search_Builder')):

/**
 * Listdom Search Builder Class.
 *
 * @class LSD_Search_Builder
 * @version	1.0.0
 */
class LSD_Search_Builder extends LSD_Base
{
    /**
     * @var LSD_Search_Helper
     */
    public $helper;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        $this->helper = new LSD_Search_Helper();
	}

    public function getAvailableFields($existingFields = array())
    {
        $existings = array();
        foreach($existingFields as $row)
        {
            if(!isset($row['filters'])) continue;

            foreach($row['filters'] as $key=>$data)
            {
                $existings[] = $key;
            }
        }

        $fields = array();

        // Text Search
        if(!in_array('s', $existings)) $fields[] = array(
            'type' => 'textsearch',
            'key' => 's',
            'title' => esc_html__('Text Search', 'listdom'),
            'methods' => $this->getFieldMethods('textsearch'),
        );

        // Category Taxonomy
        if(!in_array(LSD_Base::TAX_CATEGORY, $existings)) $fields[] = array(
            'type' => 'taxonomy',
            'key' => LSD_Base::TAX_CATEGORY,
            'title' => esc_html__('Categories', 'listdom'),
            'methods' => $this->getFieldMethods('taxonomy'),
        );

        // Location Taxonomy
        if(!in_array(LSD_Base::TAX_LOCATION, $existings)) $fields[] = array(
            'type' => 'taxonomy',
            'key' => LSD_Base::TAX_LOCATION,
            'title' => esc_html__('Locations', 'listdom'),
            'methods' => $this->getFieldMethods('taxonomy'),
        );

        // Tag Taxonomy
        if(!in_array(LSD_Base::TAX_TAG, $existings)) $fields[] = array(
            'type' => 'taxonomy',
            'key' => LSD_Base::TAX_TAG,
            'title' => esc_html__('Tags', 'listdom'),
            'methods' => $this->getFieldMethods('taxonomy'),
        );

        // Feature Taxonomy
        if(!in_array(LSD_Base::TAX_FEATURE, $existings)) $fields[] = array(
            'type' => 'taxonomy',
            'key' => LSD_Base::TAX_FEATURE,
            'title' => esc_html__('Features', 'listdom'),
            'methods' => $this->getFieldMethods('taxonomy'),
        );

        // Label Taxonomy
        if(!in_array(LSD_Base::TAX_LABEL, $existings)) $fields[] = array(
            'type' => 'taxonomy',
            'key' => LSD_Base::TAX_LABEL,
            'title' => esc_html__('Labels', 'listdom'),
            'methods' => $this->getFieldMethods('taxonomy'),
        );

        // Attributes
        if($this->isPro())
        {
            $attributes = new LSD_Taxonomies_Attribute();
            foreach($attributes->get_terms() as $attribute)
            {
                $type = get_term_meta($attribute->term_id, 'lsd_field_type', true);
                $key = 'att-'.$attribute->term_id;

                // Skip URL, Email and Separator Fields
                if(in_array($type, array('url', 'email', 'separator'))) continue;
                if(in_array($key, $existings)) continue;

                $fields[] = array(
                    'type' => 'attribute',
                    'key' => $key,
                    'title' => $attribute->name,
                    'methods' => $this->getFieldMethods($type),
                );
            }
        }

        // Price
        if(!in_array('price', $existings)) $fields[] = array(
            'type' => 'price',
            'key' => 'price',
            'title' => esc_html__('Price', 'listdom'),
            'methods' => $this->getFieldMethods('price'),
        );

        // Price Class
        if(!in_array('class', $existings)) $fields[] = array(
            'type' => 'class',
            'key' => 'class',
            'title' => esc_html__('Price Class', 'listdom'),
            'methods' => $this->getFieldMethods('class'),
        );

        // Address
        if(!in_array('address', $existings)) $fields[] = array(
            'type' => 'address',
            'key' => 'address',
            'title' => esc_html__('Address', 'listdom'),
            'methods' => $this->getFieldMethods('address'),
        );

        // Apply Filters
        return apply_filters('lsd_search_fields', $fields, $existings, $this);
	}

    public function getFieldMethods($type)
    {
        // Methods
        $methods =  array(
            'taxonomy' => array(
                'dropdown' => esc_html__('Dropdown', 'listdom'),
                'dropdown-multiple' => esc_html__('Dropdown (Multiple Selection)', 'listdom'),
                'checkboxes' => esc_html__('Checkboxes', 'listdom'),
                'radio' => esc_html__('Radio Buttons', 'listdom'),
                'text-input' => esc_html__('Text Input', 'listdom'),
            ),
            'textsearch' => array(
                'text-input' => esc_html__('Text Input', 'listdom')
            ),
            'text' => array(
                'text-input' => esc_html__('Text Input', 'listdom'),
            ),
            'textarea' => array(
                'text-input' => esc_html__('Text Input', 'listdom'),
            ),
            'number' => array(
                'number-input' => esc_html__('Number Input', 'listdom'),
                'dropdown' => esc_html__('Dropdown', 'listdom'),
                'dropdown-plus' => esc_html__('Dropdown+', 'listdom'),
            ),
            'numeric' => array(
                'number-input' => esc_html__('Number Input', 'listdom'),
            ),
            'dropdown' => array(
                'dropdown' => esc_html__('Dropdown', 'listdom'),
                'dropdown-multiple' => esc_html__('Dropdown (Multiple Selection)', 'listdom'),
                'text-input' => esc_html__('Text Input', 'listdom'),
                'checkboxes' => esc_html__('Checkboxes', 'listdom'),
                'radio' => esc_html__('Radio Buttons', 'listdom'),
            ),
            'address' => array(
                'text-input' => esc_html__('Text Input', 'listdom'),
            ),
            'price' => array(
                'dropdown-plus' => esc_html__('Dropdown+', 'listdom'),
                'mm-input' => esc_html__('Min/Max Input', 'listdom'),
            ),
            'class' => array(
                'dropdown' => esc_html__('Dropdown', 'listdom'),
            ),
            'period' => array(
                'date-range-picker' => esc_html__('Date Range Picker', 'listdom'),
            ),
        );

        // Pro Methods
        if($this->isPro())
        {
            $methods['taxonomy']['hierarchical'] = esc_html__('Hierarchical Dropdowns', 'listdom');
            $methods['address']['radius'] = esc_html__('Radius Search', 'listdom');
        }

        // Apply Filters
        $methods = apply_filters('lsd_search_field_methods', $methods);

        return isset($methods[$type]) ? $methods[$type] : array();
	}

    public function params($key, $data, $index)
    {
        $type = $this->helper->get_type_by_key($key);
        $methods = $this->getFieldMethods($type);

        // Generate output
        return $this->include_html_file('metaboxes/search/params.php', array
        (
            'return_output' => true,
            'parameters' => array
            (
                'key' => $key,
                'data' => $data,
                'type' => $type,
                'i' => $index,
                'methods' => $methods,
                'helper' => $this->helper,
            )
        ));
	}

    public function row($row, $index)
    {
        // Generate output
        return $this->include_html_file('metaboxes/search/row.php', array
        (
            'return_output' => true,
            'parameters' => array
            (
                'row' => $row,
                'i' => $index,
            )
        ));
    }
}

endif;