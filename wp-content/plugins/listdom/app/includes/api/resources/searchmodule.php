<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API_Resources_SearchModule')):

/**
 * Listdom API Search Module Resource Class.
 *
 * @class LSD_API_Resources_SearchModule
 * @version	1.0.0
 */
class LSD_API_Resources_SearchModule extends LSD_API_Resource
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function get($id)
    {
        // Resource
        $resource = new LSD_API_Resource();

        // Helper
        $helper = new LSD_Search_Helper();

        // Search Module
        $search = get_post($id);

        // Meta Values
        $metas = $resource->get_post_meta($id);

        // Form Values
        $form = (isset($metas['lsd_form']) and is_array($metas['lsd_form'])) ? $metas['lsd_form'] : array();

        // Fields
        $raw = (isset($metas['lsd_fields']) and is_array($metas['lsd_fields'])) ? $metas['lsd_fields'] : array();

        $fields = array();
        foreach($raw as $r => $row)
        {
            $filters = array();
            if(isset($row['filters']) and is_array($row['filters']))
            {
                foreach($row['filters'] as $k => $f)
                {
                    $method = isset($f['method']) ? $f['method'] : 'dropdown';

                    // Filter Params
                    $keys = array('sf-'.$f['key']);
                    $values = array();

                    $type = $helper->get_type_by_key($f['key']);
                    switch($type)
                    {
                        case 'taxonomy':

                            if($method == 'dropdown-multiple' or $method == 'checkboxes') $keys = array('sf-'.$f['key'].'[]');

                            $controller = new LSD_API_Controllers_Taxonomies();

                            $terms = $controller->hierarchy($f['key'], 0, (isset($f['hide_empty']) ? $f['hide_empty'] : NULL));
                            $values = LSD_API_Resources_Taxonomy::collection($terms);

                            break;

                        case 'text':

                            $keys = array('sf-'.$f['key'].'-lk');
                            break;

                        case 'number':

                            $keys = array('sf-'.$f['key'].'-eq');
                            if($method == 'dropdown-plus') $keys = array('sf-'.$f['key'].'-grq');

                            $values = $helper->get_terms($f, true);

                            break;

                        case 'dropdown':

                            $keys = array('sf-'.$f['key'].'-eq');
                            if($method == 'dropdown-multiple' or $method == 'checkboxes') $keys = array('sf-'.$f['key'].'-in[]');

                            $values = $helper->get_terms($f, true);

                            break;

                        case 'price':

                            if($method == 'dropdown-plus') $keys = array('sf-att-'.$f['key'].'-grq');
                            elseif($method == 'mm-input')
                            {
                                $keys = array(
                                    'sf-att-'.$f['key'].'-bt-min',
                                    'sf-att-'.$f['key'].'-bt-max'
                                );
                            }

                            break;

                        case 'address':

                            $keys = array('sf-att-'.$f['key'].'-lk');
                            break;
                    }

                    $f['key'] = $keys;
                    $f['values'] = $values;

                    $filters[$k] = $f;
                }
            }

            $fields[$r] = array(
                'type' => (isset($row['type']) ? $row['type'] : 'row'),
            );

            if(isset($row['filters'])) $fields[$r]['filters'] = $filters;
            if(isset($row['buttons'])) $fields[$r]['buttons'] = $row['buttons'];
        }

        return apply_filters('lsd_api_resource_searchmodule', array(
            'id' => $search->ID,
            'data' => array(
                'ID' => $search->ID,
                'title' => get_the_title($search),
                'status' => $search->post_status,
                'form' => array(
                    'style' => (isset($form['style']) ? $form['style'] : NULL),
                    'page' => ((isset($form['page']) and trim($form['page'])) ? get_permalink($form['page']) : NULL),
                    'shortcode' => (isset($form['shortcode']) ? $form['shortcode'] : NULL),
                ),
                'fields' => $fields,
            )
        ), $id);
	}

    public static function collection($ids)
    {
        $items = array();
        foreach($ids as $id) $items[] = self::get($id);

        return $items;
    }
}

endif;