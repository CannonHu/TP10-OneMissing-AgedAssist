<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Dummy')):

/**
 * Listdom Dummy Data Class.
 *
 * @class LSD_Dummy
 * @version	1.0.0
 */
class LSD_Dummy extends LSD_Base
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
        add_action('lsd_admin_dashboard_tabs', array($this, 'tab'));
        add_action('lsd_admin_dashboard_contents', array($this, 'content'));

        // Activate
        add_action('wp_ajax_lsd_dummy', array($this, 'dummy'));
    }

    public function tab($tab)
    {
        echo '<a class="nav-tab '.($tab == 'dummy-data' ? 'nav-tab-active' : '').'" href="'.esc_url(admin_url('admin.php?page=listdom&tab=dummy-data')).'">'.esc_html__('Dummy Data', 'listdom').'</a>';
    }

    public function content($tab)
    {
        // It's not Activation Tab
        if($tab != 'dummy-data') return;

        $this->include_html_file('menus/dashboard/tabs/dummy-data.php');
    }

    public function dummy()
    {
        // Include Needed Library
        if(!function_exists('post_exists')) require_once(ABSPATH . 'wp-admin/includes/post.php');

        // Categories
        foreach(array(
            array('label' => esc_html__('Beauty Salon', 'listdom'), 'color' => '#dd3333', 'icon' => 'fa fa-user'),
            array('label' => esc_html__('Cafe', 'listdom'), 'color' => '#81d742', 'icon' => 'fa fa-coffee'),
            array('label' => esc_html__('Hospital', 'listdom'), 'color' => '#000000', 'icon' => 'fa fa-heartbeat'),
            array('label' => esc_html__('Hotel', 'listdom'), 'color' => '#1d7ed3', 'icon' => 'fa fa-bed'),
            array('label' => esc_html__('Restaurant', 'listdom'), 'color' => '#8224e3', 'icon' => 'fas fa-utensil-spoon'),
            array('label' => esc_html__('Super Market', 'listdom'), 'color' => '#dd9933', 'icon' => 'fa fa-shopping-basket'),
        ) as $category)
        {
            // Don't add it again if exists
            if(term_exists($category['label'], LSD_Base::TAX_CATEGORY)) continue;

            // Term
            $result = wp_insert_term(
                $category['label'],
                LSD_Base::TAX_CATEGORY
            );

            // Icon
            update_term_meta($result['term_id'], 'lsd_icon', $category['icon']);

            // Color
            update_term_meta($result['term_id'], 'lsd_color', $category['color']);
        }

        // Locations
        foreach(array(
            array('label' => esc_html__('Florida', 'listdom')),
            array('label' => esc_html__('London', 'listdom')),
            array('label' => esc_html__('Los Angeles', 'listdom')),
            array('label' => esc_html__('New York', 'listdom')),
            array('label' => esc_html__('Paris', 'listdom')),
            array('label' => esc_html__('Rome', 'listdom')),
            array('label' => esc_html__('Madrid', 'listdom')),
        ) as $location)
        {
            // Don't add it again if exists
            if(term_exists($location['label'], LSD_Base::TAX_LOCATION)) continue;

            // Term
            wp_insert_term(
                $location['label'],
                LSD_Base::TAX_LOCATION
            );
        }

        // Tags
        foreach(array(
            array('label' => esc_html__('Discount', 'listdom')),
            array('label' => esc_html__('Easy Access', 'listdom')),
            array('label' => esc_html__('Parking Friendly', 'listdom')),
            array('label' => esc_html__('Recommended', 'listdom')),
        ) as $tag)
        {
            // Don't add it again if exists
            if(term_exists($tag['label'], LSD_Base::TAX_TAG)) continue;

            // Term
            wp_insert_term(
                $tag['label'],
                LSD_Base::TAX_TAG
            );
        }

        // Features
        foreach(array(
            array('label' => esc_html__('Free Wifi', 'listdom'), 'icon' => 'fas fa-wifi'),
            array('label' => esc_html__('Live Music', 'listdom'), 'icon' => 'fa fa-music'),
            array('label' => esc_html__('Valet', 'listdom'), 'icon' => 'fa fa-car'),
        ) as $feature)
        {
            // Don't add it again if exists
            if(term_exists($feature['label'], LSD_Base::TAX_FEATURE)) continue;

            // Term
            $result = wp_insert_term(
                $feature['label'],
                LSD_Base::TAX_FEATURE
            );

            // Icon
            update_term_meta($result['term_id'], 'lsd_icon', $feature['icon']);
        }

        // Labels
        foreach(array(
            array('label' => esc_html__('Exclusive', 'listdom'), 'color' => '#1d7ed3'),
            array('label' => esc_html__('Hot Offer', 'listdom'), 'color' => '#dd3333'),
            array('label' => esc_html__('Must See', 'listdom'), 'color' => '#dd9933'),
            array('label' => esc_html__('Recommended', 'listdom'), 'color' => '#000000'),
        ) as $label)
        {
            // Don't add it again if exists
            if(term_exists($label['label'], LSD_Base::TAX_LABEL)) continue;

            // Term
            $result = wp_insert_term(
                $label['label'],
                LSD_Base::TAX_LABEL
            );

            // Color
            update_term_meta($result['term_id'], 'lsd_color', $label['color']);
        }

        if($this->isPro())
        {
            // Attributes
            foreach(array(
                array('label' => esc_html__('Discount', 'listdom'), 'type' => 'text', 'icon' => 'far fa-money-bill-alt', 'index' => '1.00', 'values' => ''),
                array('label' => esc_html__('Pets Allowed', 'listdom'), 'type' => 'dropdown', 'icon' => 'far fa-heart', 'index' => '2.00', 'values' => 'Yes,No,Only Cats,Only Dogs'),
                array('label' => esc_html__('Parking Capacity', 'listdom'), 'type' => 'number', 'icon' => 'fa fa-car', 'index' => '3.00', 'values' => ''),
            ) as $attribute)
            {
                // Don't add it again if exists
                if(term_exists($attribute['label'], LSD_Base::TAX_ATTRIBUTE)) continue;

                // Term
                $result = wp_insert_term(
                    $attribute['label'],
                    LSD_Base::TAX_ATTRIBUTE
                );

                // Meta Data
                update_term_meta($result['term_id'], 'lsd_field_type', $attribute['type']);
                update_term_meta($result['term_id'], 'lsd_icon', $attribute['icon']);
                update_term_meta($result['term_id'], 'lsd_index', $attribute['index']);
                update_term_meta($result['term_id'], 'lsd_values', $attribute['values']);
                update_term_meta($result['term_id'], 'lsd_all_categories', 1);
                update_term_meta($result['term_id'], 'lsd_categories', array());
            }
        }

        // Shortcodes
        $shortcodes = array(
            array(
                'title'=>'List',
                'meta'=>array(
                    'lsd_skin'=>'list',
                    'lsd_display'=>array(
                        'skin' => 'list',
                        'list' => array(
                            'style' => 'style1',
                            'map_position' => 'top',
                            'clustering' => 1,
                            'clustering_images' => 'img/cluster2/m',
                            'mapobject_onclick' => 'infowindow',
                            'mapsearch' => 0,
                            'maplimit' => 300,
                            'limit' => 12,
                            'load_more' => 1,
                            'display_labels' => 1,
                            'display_share_buttons' => 1,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
            array(
                'title'=>'Grid',
                'meta'=>array(
                    'lsd_skin'=>'grid',
                    'lsd_display'=>array(
                        'skin' => 'grid',
                        'grid' => array(
                            'style' => 'style1',
                            'map_position' => 'top',
                            'clustering' => 1,
                            'clustering_images' => 'img/cluster2/m',
                            'mapobject_onclick' => 'infowindow',
                            'mapsearch' => 0,
                            'maplimit' => 300,
                            'columns' => 3,
                            'limit' => 12,
                            'load_more' => 1,
                            'display_labels' => 1,
                            'display_share_buttons' => 1,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
            array(
                'title'=>'Single Map',
                'meta'=>array(
                    'lsd_skin'=>'singlemap',
                    'lsd_display'=>array(
                        'skin' => 'singlemap',
                        'singlemap' => array(
                            'clustering' => 1,
                            'clustering_images' => 'img/cluster2/m',
                            'mapobject_onclick' => 'infowindow',
                            'mapsearch' => 1,
                            'maplimit' => 300,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
            array(
                'title'=>'List + Grid',
                'meta'=>array(
                    'lsd_skin'=>'listgrid',
                    'lsd_display'=>array(
                        'skin' => 'listgrid',
                        'listgrid' => array(
                            'style' => 'style1',
                            'map_position' => 'top',
                            'clustering' => 1,
                            'clustering_images' => 'img/cluster2/m',
                            'mapobject_onclick' => 'infowindow',
                            'mapsearch' => 1,
                            'maplimit' => 300,
                            'default_view' => 'grid',
                            'columns' => 3,
                            'limit' => 12,
                            'load_more' => 1,
                            'display_labels' => 1,
                            'display_share_buttons' => 1,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
            array(
                'title'=>'Halfmap',
                'meta'=>array(
                    'lsd_skin'=>'halfmap',
                    'lsd_display'=>array(
                        'skin' => 'halfmap',
                        'halfmap' => array(
                            'style' => 'style1',
                            'map_position' => 'left',
                            'clustering' => 1,
                            'clustering_images' => 'img/cluster2/m',
                            'mapobject_onclick' => 'infowindow',
                            'mapsearch' => 1,
                            'maplimit' => 300,
                            'map_height' => 500,
                            'columns' => 2,
                            'limit' => 12,
                            'load_more' => 1,
                            'display_labels' => 1,
                            'display_share_buttons' => 1,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
            array(
                'title'=>'Table',
                'meta'=>array(
                    'lsd_skin'=>'table',
                    'lsd_display'=>array(
                        'skin' => 'table',
                        'table' => array(
                            'style' => 'style1',
                            'limit' => 12,
                            'load_more' => 1,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
            array(
                'title'=>'Masonry',
                'meta'=>array(
                    'lsd_skin'=>'masonry',
                    'lsd_display'=>array(
                        'skin' => 'masonry',
                        'masonry' => array(
                            'style' => 'style1',
                            'filter_by' => 'listdom-category',
                            'columns' => 3,
                            'limit' => 12,
                            'display_labels' => 1,
                            'display_share_buttons' => 1,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
            array(
                'title'=>'Carousel',
                'meta'=>array(
                    'lsd_skin'=>'carousel',
                    'lsd_display'=>array(
                        'skin' => 'carousel',
                        'carousel' => array(
                            'style' => 'style1',
                            'columns' => 3,
                            'limit' => 8,
                            'display_labels' => 1,
                            'display_share_buttons' => 1,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
            array(
                'title'=>'Slider',
                'meta'=>array(
                    'lsd_skin'=>'slider',
                    'lsd_display'=>array(
                        'skin' => 'slider',
                        'slider' => array(
                            'style' => 'style1',
                            'limit' => 8,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
            array(
                'title'=>'Cover',
                'meta'=>array(
                    'lsd_skin'=>'cover',
                    'lsd_display'=>array(
                        'skin' => 'cover',
                        'cover' => array(
                            'style' => 'style1',
                            'listing' => NULL,
                        )
                    ),
                    'lsd_filter'=>array(),
                    'lsd_mapcontrols'=>LSD_Options::defaults('mapcontrols'),
                    'lsd_sorts'=>LSD_Options::defaults('sorts')
                )
            ),
        );

        foreach($shortcodes as $shortcode)
        {
            // Shortcode Exists
            if(post_exists($shortcode['title'], 'listdom')) continue;

            $post = array('post_title'=>$shortcode['title'], 'post_content'=>'listdom', 'post_type'=>LSD_Base::PTYPE_SHORTCODE, 'post_status'=>'publish');
            $post_id = wp_insert_post($post);

            foreach($shortcode['meta'] as $key=>$value) update_post_meta($post_id, $key, $value);
        }

        // Searches
        $searches = array(
            array(
                'title'=>'Default Search',
                'meta'=>array(
                    'lsd_form'=>array(
                        'style'=>'default',
                        'page'=>'',
                        'shortcode'=>'',
                    ),
                    'lsd_fields'=>array(
                        1=>array(
                            'type'=>'row',
                            'filters'=>array(
                                's'=>array(
                                    'key'=>'s',
                                    'title'=>'Text Search',
                                    'method'=>'text-input',
                                    'placeholder'=>'',
                                    'max_placeholder'=>'',
                                    'default_value'=>'',
                                    'max_default_value'=>'',
                                    'min'=>'0',
                                    'max'=>'100',
                                    'increment'=>'10',
                                    'th_separator'=>'1',
                                ),
                            ),
                            'buttons'=>'1',
                        ),
                        2=>array(
                            'type'=>'more_options',
                        ),
                        3=>array(
                            'type'=>'row',
                            'filters'=>array(
                                'listdom-category'=>array(
                                    'key'=>'listdom-category',
                                    'title'=>'Categories',
                                    'method'=>'dropdown',
                                    'hide_empty'=>'1',
                                    'placeholder'=>'',
                                    'max_placeholder'=>'',
                                    'default_value'=>'',
                                    'max_default_value'=>'',
                                    'min'=>'0',
                                    'max'=>'100',
                                    'increment'=>'10',
                                    'th_separator'=>'1',
                                ),
                                'listdom-location'=>array(
                                    'key'=>'listdom-location',
                                    'title'=>'Locations',
                                    'method'=>'dropdown',
                                    'hide_empty'=>'1',
                                    'placeholder'=>'',
                                    'max_placeholder'=>'',
                                    'default_value'=>'',
                                    'max_default_value'=>'',
                                    'min'=>'0',
                                    'max'=>'100',
                                    'increment'=>'10',
                                    'th_separator'=>'1',
                                ),
                                'listdom-label'=>array(
                                    'key'=>'listdom-label',
                                    'title'=>'Labels',
                                    'method'=>'dropdown',
                                    'hide_empty'=>'1',
                                    'placeholder'=>'',
                                    'max_placeholder'=>'',
                                    'default_value'=>'',
                                    'max_default_value'=>'',
                                    'min'=>'0',
                                    'max'=>'100',
                                    'increment'=>'10',
                                    'th_separator'=>'1',
                                ),
                            ),
                            'buttons'=>'0',
                        ),
                    ),
                )
            ),
        );

        foreach($searches as $search)
        {
            // Search Exists
            if(post_exists($search['title'], 'listdom')) continue;

            $post = array('post_title'=>$search['title'], 'post_content'=>'listdom', 'post_type'=>LSD_Base::PTYPE_SEARCH, 'post_status'=>'publish');
            $post_id = wp_insert_post($post);

            foreach($search['meta'] as $key=>$value) update_post_meta($post_id, $key, $value);
        }

        $this->response(array('success'=>1));
    }
}

endif;