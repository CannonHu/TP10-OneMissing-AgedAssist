<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API_Resources_Attribute')):

/**
 * Listdom API Attribute Resource Class.
 *
 * @class LSD_API_Resources_Attribute
 * @version	1.0.0
 */
class LSD_API_Resources_Attribute extends LSD_API_Resource
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function listing($id)
    {
        // Listing Category
        $entity = new LSD_Entity_Listing($id);
        $category = $entity->get_data_category();

        $values = get_post_meta($id, 'lsd_attributes', true);

        $attributes = array();
        foreach($values as $attribute_id => $value)
        {
            // Term
            $term = get_term($attribute_id);

            // Get all category status
            $all_categories = get_term_meta($term->term_id, 'lsd_all_categories', true);
            if(trim($all_categories) == '') $all_categories = 1;

            // Get specific categories
            $categories = get_term_meta($term->term_id, 'lsd_categories', true);
            if($all_categories) $categories = array();

            // This attribute is not specified for listing category
            if(!$all_categories and (count($categories) and !isset($categories[$category->term_id]))) continue;

            $attributes[] = array(
                'id' => $attribute_id,
                'name' => $term->name,
                'value' => $value,
            );
        }

        return apply_filters('lsd_api_resource_attribute', $attributes, $id);
    }
}

endif;