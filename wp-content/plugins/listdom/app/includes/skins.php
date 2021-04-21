<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Skins')):

/**
 * Listdom Skins Class.
 *
 * @class LSD_Skins
 * @version	1.0.0
 */
class LSD_Skins extends LSD_Base
{
    public $args = array();
    public $listings = array();
    public $atts = array();
    public $skin_options = array();
    public $filter_options = array();
    public $search_options = array();
    public $sm_shortcode = array();
    public $sm_position = array();
    public $mapcontrols = array();
    public $sorts = array();
    public $sortbar = false;
    public $orderby = 'post_date';
    public $order = 'DESC';
    public $skin = 'list';
    public $settings = array();
    public $id;
    public $next_page = 1;
    public $page = 1;
    public $limit = 300;
    public $found_listings;
    public $style;
    public $default_style;
    public $load_more = false;
    public $display_labels = false;
    public $display_image = true;
    public $display_share_buttons = false;
    public $columns = 1;
    public $default_view = 'grid';
    public $html_class = '';
    public $widget = false;
    public $post_id;
    public $mapsearch = false;
    public $autoGPS = false;
    public $maxBounds = array();
    public $map_provider = 'leaflet';

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        $this->settings = LSD_Options::settings();
	}
    
    public function init()
    {
        // Add Filters
        add_filter('posts_join', array($this, 'query_join'), 10, 2);
        add_filter('posts_where', array($this, 'query_where'), 10, 2);

        $singlemap = new LSD_Skins_Singlemap();
        $singlemap->init();

        $list = new LSD_Skins_List();
        $list->init();

        $grid = new LSD_Skins_Grid();
        $grid->init();

        $listgrid = new LSD_Skins_Listgrid();
        $listgrid->init();

        $halfmap = new LSD_Skins_Halfmap();
        $halfmap->init();

        $table = new LSD_Skins_Table();
        $table->init();

        $cover = new LSD_Skins_Cover();
        $cover->init();

        $carousel = new LSD_Skins_Carousel();
        $carousel->init();

        $slider = new LSD_Skins_Slider();
        $slider->init();

        $masonry = new LSD_Skins_Masonry();
        $masonry->init();
    }

    public function start($atts)
    {
        $this->atts = apply_filters('lsd_skins_atts', $atts);
        $this->id = LSD_id::get((isset($this->atts['id']) ? sanitize_text_field($this->atts['id']) : mt_rand(100, 999)));

        // Skin Options
        $this->skin_options = (isset($this->atts['lsd_display']) and isset($this->atts['lsd_display'][$this->skin])) ? $this->atts['lsd_display'][$this->skin] : array();

        // Search Options
        $this->search_options = isset($this->atts['lsd_search']) ? $this->atts['lsd_search'] : array();
        $this->sm_shortcode = (isset($this->search_options['shortcode']) and trim($this->search_options['shortcode'])) ? $this->search_options['shortcode'] : NULL;
        $this->sm_position = (isset($this->search_options['position']) and trim($this->search_options['position'])) ? $this->search_options['position'] : 'top';

        // Filter Options
        $this->filter_options = isset($this->atts['lsd_filter']) ? $this->atts['lsd_filter'] : array();

        // Map Controls Options
        $this->mapcontrols = isset($this->atts['lsd_mapcontrols']) ? $this->atts['lsd_mapcontrols'] : array();

        // Default Options
        $this->map_provider = (isset($this->skin_options['map_provider']) and $this->skin_options['map_provider']) ? sanitize_text_field($this->skin_options['map_provider']) : false;
        $this->style = (isset($this->skin_options['style']) and $this->skin_options['style']) ? sanitize_text_field($this->skin_options['style']) : $this->default_style;
        $this->display_image = ($this->isLite() or !isset($this->skin_options['display_image']) or (isset($this->skin_options['display_image']) and $this->skin_options['display_image'])) ? true : false;
        $this->load_more = (isset($this->skin_options['load_more']) and $this->skin_options['load_more']) ? true : false;
        $this->display_labels = (isset($this->skin_options['display_labels']) and $this->skin_options['display_labels']) ? true : false;
        $this->display_share_buttons = (isset($this->skin_options['display_share_buttons']) and $this->skin_options['display_share_buttons']) ? true : false;
        $this->columns = (isset($this->skin_options['columns']) and $this->skin_options['columns']) ? sanitize_text_field($this->skin_options['columns']) : 1;
        $this->default_view = isset($this->skin_options['default_view']) ? sanitize_text_field($this->skin_options['default_view']) : 'grid';

        // Map Search Options
        $this->mapsearch = (isset($this->skin_options['mapsearch']) and $this->skin_options['mapsearch']) ? true : false;
        $this->autoGPS = (isset($this->skin_options['auto_gps']) and $this->skin_options['auto_gps']) ? true : false;
        $this->maxBounds = apply_filters('lsd_map_max_bounds', ((isset($this->skin_options['max_bounds']) and is_array($this->skin_options['max_bounds'])) ? $this->skin_options['max_bounds'] : array()));

        // HTML Class
        $this->html_class = (isset($this->atts['html_class']) and trim($this->atts['html_class'])) ? sanitize_text_field($this->atts['html_class']) : '';

        // Is it Widget?
        $this->widget = (isset($this->atts['widget']) and $this->atts['widget']) ? true : false;

        // Disable Pro features
        if($this->isLite())
        {
            // Disable Map Search
            $this->mapsearch = false;

            // Disable GPS feature
            if(isset($this->mapcontrols['gps'])) $this->mapcontrols['gps'] = '0';

            // Disable Draw feature
            if(isset($this->mapcontrols['draw'])) $this->mapcontrols['draw'] = '0';
        }

        // Set to Payload Options
        LSD_Payload::set('shortcode', $this);
    }

    public function after_start()
    {
    }

    public function query()
    {
        // Post Type
        $this->args['post_type'] = LSD_Base::PTYPE_LISTING;
        $this->args['ignore_sticky_posts'] = true;

        // Status
        $this->args['post_status'] = $this->query_status();

        // Keyword
        $this->args['s'] = $this->query_keyword();

        // Taxonomy
        $this->args['tax_query'] = $this->query_tax();

        // Meta
        $this->args['meta_query'] = $this->query_meta();

        // Author
        $this->args['author'] = $this->query_author();

        // Include / Exclude
        $this->query_ixclude();

        // Radius
        $this->query_radius();

        // Pagination Options
        $paged = $this->page;
        $this->limit = (isset($this->skin_options['limit']) and trim($this->skin_options['limit'])) ? sanitize_text_field($this->skin_options['limit']) : 300;

        $this->args['posts_per_page'] = $this->limit;
        $this->args['paged'] = $paged;

        // Sort Query
        $this->sort();

        // Init the Data Search
        $this->args['lsd-init'] = true;
    }

    public function query_keyword()
    {
        return (isset($this->filter_options['s']) and trim($this->filter_options['s']) != '') ? sanitize_text_field($this->filter_options['s']) : NULL;
    }

    public function query_status()
    {
        return (isset($this->filter_options['status']) ? $this->filter_options['status'] : array('publish'));
    }

    public function query_tax()
    {
        $tax_query = array('relation'=>'AND');

        // Categories
        if(isset($this->filter_options[LSD_Base::TAX_CATEGORY]) and is_array($this->filter_options[LSD_Base::TAX_CATEGORY]) and count($this->filter_options[LSD_Base::TAX_CATEGORY]))
        {
            $tax_query[] = array(
                'taxonomy'=>LSD_Base::TAX_CATEGORY,
                'field'=>'term_id',
                'terms'=>$this->filter_options[LSD_Base::TAX_CATEGORY],
                'operator'=>apply_filters('lsd_search_'.LSD_Base::TAX_CATEGORY.'_operator', 'IN')
            );
        }

        // Locations
        if(isset($this->filter_options[LSD_Base::TAX_LOCATION]) and is_array($this->filter_options[LSD_Base::TAX_LOCATION]) and count($this->filter_options[LSD_Base::TAX_LOCATION]))
        {
            $tax_query[] = array(
                'taxonomy'=>LSD_Base::TAX_LOCATION,
                'field'=>'term_id',
                'terms'=>$this->filter_options[LSD_Base::TAX_LOCATION],
                'operator'=>apply_filters('lsd_search_'.LSD_Base::TAX_LOCATION.'_operator', 'IN')
            );
        }

        // Tags
        if(isset($this->filter_options[LSD_Base::TAX_TAG]))
        {
            if(is_array($this->filter_options[LSD_Base::TAX_TAG]))
            {
                $tax_query[] = array(
                    'taxonomy'=>LSD_Base::TAX_TAG,
                    'field'=>'term_id',
                    'terms'=>$this->filter_options[LSD_Base::TAX_TAG],
                    'operator'=>apply_filters('lsd_search_'.LSD_Base::TAX_TAG.'_operator', 'IN')
                );
            }
            elseif(trim($this->filter_options[LSD_Base::TAX_TAG]))
            {
                $tax_query[] = array(
                    'taxonomy'=>LSD_Base::TAX_TAG,
                    'field'=>'name',
                    'terms'=>explode(',', sanitize_text_field(trim($this->filter_options[LSD_Base::TAX_TAG], ', '))),
                    'operator'=>apply_filters('lsd_search_'.LSD_Base::TAX_TAG.'_operator', 'IN')
                );
            }
        }

        // Features
        if(isset($this->filter_options[LSD_Base::TAX_FEATURE]) and is_array($this->filter_options[LSD_Base::TAX_FEATURE]) and count($this->filter_options[LSD_Base::TAX_FEATURE]))
        {
            $tax_query[] = array(
                'taxonomy'=>LSD_Base::TAX_FEATURE,
                'field'=>'term_id',
                'terms'=>$this->filter_options[LSD_Base::TAX_FEATURE],
                'operator'=>apply_filters('lsd_search_'.LSD_Base::TAX_FEATURE.'_operator', 'IN')
            );
        }

        // Labels
        if(isset($this->filter_options[LSD_Base::TAX_LABEL]) and is_array($this->filter_options[LSD_Base::TAX_LABEL]) and count($this->filter_options[LSD_Base::TAX_LABEL]))
        {
            $tax_query[] = array(
                'taxonomy'=>LSD_Base::TAX_LABEL,
                'field'=>'term_id',
                'terms'=>$this->filter_options[LSD_Base::TAX_LABEL],
                'operator'=>apply_filters('lsd_search_'.LSD_Base::TAX_LABEL.'_operator', 'IN')
            );
        }

        return $tax_query;
    }

    public function query_meta()
    {
        $meta_query = array();

        if(isset($this->filter_options['attributes']) and is_array($this->filter_options['attributes']) and count($this->filter_options['attributes']))
        {
            foreach($this->filter_options['attributes'] as $key=>$value)
            {
                if((is_array($value) and !count($value)) or (!is_array($value) and trim($value) == '')) continue;

                $q = LSD_Query::attribute($key, $value);

                if(!$q) continue;

                // Add to Meta Query
                $meta_query[] = $q;
            }
        }

        return $meta_query;
    }

    public function query_author()
    {
        $authors = '';

        // Authors
        if(isset($this->filter_options['authors']) and is_array($this->filter_options['authors']) and count($this->filter_options['authors']))
        {
            $authors = sanitize_text_field(implode(',', $this->filter_options['authors']));
        }

        return $authors;
    }

    public function query_ixclude()
    {
        // Include
        if(isset($this->filter_options['include']) and is_array($this->filter_options['include']) and count($this->filter_options['include']))
        {
            $this->args['post__in'] = $this->filter_options['include'];
        }

        // Exclude
        if(isset($this->filter_options['exclude']) and is_array($this->filter_options['exclude']) and count($this->filter_options['exclude']))
        {
            $this->args['post__not_in'] = $this->filter_options['exclude'];
        }
    }

    public function query_radius()
    {
        // Include
        if(isset($this->filter_options['circle']) and is_array($this->filter_options['circle']) and count($this->filter_options['circle']) and isset($this->filter_options['circle']['center']) and isset($this->filter_options['circle']['radius']))
        {
            $main = new LSD_Main();
            $geopoint = $main->geopoint($this->filter_options['circle']['center']);

            if(isset($geopoint[0]) and $geopoint[0] and isset($geopoint[1]) and $geopoint[1])
            {
                $this->args['lsd-circle'] = array(
                    'center' => array($geopoint[0], $geopoint[1]),
                    'radius' => ((int) $this->filter_options['circle']['radius'])
                );
            }
        }
    }

    public function sort()
    {
        // Sort Options
        $this->sorts = isset($this->atts['lsd_sorts']) ? $this->atts['lsd_sorts'] : LSD_Options::defaults('sorts');

        // Sortbar Status
        $this->sortbar = (isset($this->sorts['display']) and $this->sorts['display']) ? true : false;

        // Order and Order By
        $this->orderby = (isset($this->sorts['default']) and isset($this->sorts['default']['orderby'])) ? $this->sorts['default']['orderby'] : 'post_date';
        $this->order = (isset($this->sorts['default']) and isset($this->sorts['default']['order'])) ? $this->sorts['default']['order'] : 'DESC';

        // Sort by Meta
        if(strpos($this->orderby, 'lsd_') !== false)
        {
            $this->args['orderby'] = 'meta_value_num';
            $this->args['meta_key'] = $this->orderby;
        }
        else $this->args['orderby'] = $this->orderby;

        // Order
        $this->args['order'] = $this->order;
    }

    /**
     * @param string $join
     * @param WP_Query $wp_query
     * @return string
     */
    public function query_join($join, $wp_query)
    {
        if(is_string($wp_query->query_vars['post_type']) and $wp_query->query_vars['post_type'] == LSD_Base::PTYPE_LISTING and $wp_query->get('lsd-init', false))
        {
            global $wpdb;
            $join .= " LEFT JOIN `".$wpdb->prefix."lsd_data` AS lsddata ON `".$wpdb->prefix."posts`.`ID` = lsddata.`id` ";
        }

        return $join;
    }

    /**
     * @param string $where
     * @param WP_Query $wp_query
     * @return string
     */
    public function query_where($where, $wp_query)
    {
        if(is_string($wp_query->query_vars['post_type']) and $wp_query->query_vars['post_type'] == LSD_Base::PTYPE_LISTING and $wp_query->get('lsd-init', false))
        {
            // Boundary Search
            if($boundary = $wp_query->get('lsd-boundary', false))
            {
                $where .= " AND lsddata.`latitude` >= '".$boundary['min_latitude']."' AND lsddata.`latitude` <= '".$boundary['max_latitude']."' AND lsddata.`longitude` >= '".$boundary['min_longitude']."' AND lsddata.`longitude` <= '".$boundary['max_longitude']."'";
            }

            // Circle Search
            if($circle = $wp_query->get('lsd-circle', false))
            {
                $where .= " AND ((6371000 * acos(cos(radians(".$circle['center'][0].")) * cos(radians(lsddata.`latitude`)) * cos(radians(lsddata.`longitude`) - radians(".$circle['center'][1].")) + sin(radians(".$circle['center'][0].")) * sin(radians(lsddata.`latitude`)))) < ".($circle['radius']) .")";
            }

            // Polygon Search
            if($polygon = $wp_query->get('lsd-polygon', false))
            {
                // Libraries
                $db = new LSD_db();
                $shape = new LSD_Shape();

                if(version_compare($db->version(), '5.6.1', '>='))
                {
                    $sql_function1 = 'ST_Contains';
                    $sql_function2 = 'ST_GeomFromText';
                }
                else
                {
                    $sql_function1 = 'Contains';
                    $sql_function2 = 'GeomFromText';
                }

                $polygon = $shape->toPolygon((isset($polygon['points']) ? $polygon['points'] : array()));

                $polygon_str = '';
                foreach($polygon as $polygon_point) $polygon_str .= $polygon_point[0].' '.$polygon_point[1].', ';
                $polygon_str = trim($polygon_str, ', ');

                $where .= " AND ".$sql_function1."($sql_function2('Polygon((".esc_sql($polygon_str)."))'), lsddata.`point`) = 1";
            }

            // Apply Filters
            $where = apply_filters('lsd_where_query', $where, $this, $wp_query);
        }

        return $where;
    }

    public function search($params = array())
    {
        $args = wp_parse_args($params, $this->args);

        // Apply Filter
        $args = apply_filters('lsd_before_search', $args, $this);

        // Random Order
        if(isset($args['orderby']) and $args['orderby'] == 'rand')
        {
            $seed = (isset($this->atts['seed']) and isset($args['paged']) and $args['paged'] != 1) ? $this->atts['seed'] : rand(10000, 99999);

            $args['orderby'] = 'RAND('.$seed.')';
            $this->atts['seed'] = $seed;
        }

        // The Query
        $query = new WP_Query($args);

        $ids = array();
        if($query->have_posts())
        {
            // The Loop
            while($query->have_posts())
            {
                $query->the_post();
                $ids[] = get_the_ID();
            }

            // Total Count of Results
            $this->found_listings = $query->found_posts;

            // Next Page
            $this->next_page = (isset($args['paged']) ? ($args['paged']+1) : 1);

            // Restore original Post Data
            wp_reset_postdata();
        }

        return $ids;
    }

    /**
     * @param array $search
     * @param string $limitType
     * @return array
     */
    public function apply_search($search, $limitType = 'listings')
    {
        // Search Args
        $args = array();

        // Order
        $args['orderby'] = isset($search['orderby']) ? sanitize_text_field($search['orderby']) : $this->orderby;
        $args['order'] = isset($search['order']) ? sanitize_text_field($search['order']) : $this->order;

        // Limit
        $limit = ((isset($search['limit']) and trim($search['limit'])) ? $search['limit'] : (($limitType == 'map' and isset($this->skin_options['maplimit'])) ? sanitize_text_field($this->skin_options['maplimit']) : (isset($this->skin_options['limit']) ? sanitize_text_field($this->skin_options['limit']) : 12)));

        $args['posts_per_page'] = $limit;
        $this->limit = $limit;

        // Page
        $args['paged'] = isset($search['page']) ? sanitize_text_field($search['page']) : 1;

        // Search Parameters
        $sf = (isset($search['sf']) and is_array($search['sf'])) ? $search['sf'] : array();
        $shape = isset($sf['shape']) ? sanitize_text_field($sf['shape']) : NULL;

        // Boundary Search
        if(!$shape and isset($sf['min_latitude']) and trim($sf['min_latitude']) and
            isset($sf['max_latitude']) and trim($sf['max_latitude']) and
            isset($sf['min_longitude']) and trim($sf['min_longitude']) and
            isset($sf['max_longitude']) and trim($sf['max_longitude']))
        {
            $args['lsd-boundary'] = array(
                'min_latitude' => $sf['min_latitude'],
                'max_latitude' => $sf['max_latitude'],
                'min_longitude' => $sf['min_longitude'],
                'max_longitude' => $sf['max_longitude'],
            );
        }

        // Rectangle Search
        if($shape == 'rectangle')
        {
            $args['lsd-boundary'] = array(
                'min_latitude' => $sf['rect_min_latitude'],
                'max_latitude' => $sf['rect_max_latitude'],
                'min_longitude' => $sf['rect_min_longitude'],
                'max_longitude' => $sf['rect_max_longitude'],
            );
        }

        // Circle Search
        if($shape == 'circle')
        {
            $args['lsd-circle'] = array(
                'center' => array($sf['circle_latitude'], $sf['circle_longitude']),
                'radius' => $sf['circle_radius']
            );
        }

        // Polygon Search
        if($shape == 'polygon')
        {
            $args['lsd-polygon'] = array(
                'points' => $sf['polygon']
            );
        }

        return $this->args = wp_parse_args($args, $this->args);
    }

    public function fetch()
    {
        // Get Listings
        $this->listings = $this->search();
    }

    public function setLimit($type = 'listings', $limit = NULL)
    {
        if(!$limit) $this->args['posts_per_page'] = ($type == 'map' ? sanitize_text_field($this->skin_options['maplimit']) : sanitize_text_field($this->skin_options['limit']));
        else $this->args['posts_per_page'] = $limit;
    }

    public function tpl()
    {
        return lsd_template('skins/'.$this->skin.'/tpl.php');
    }

    public function listings_html()
    {
        $path = lsd_template('skins/'.$this->skin.'/render.php');

        // File not Found!
        if(!LSD_File::exists($path)) return '';

        ob_start();
        include $path;
        $output = ob_get_clean();

        // No Listing Found
        if(trim($output) === '')
        {
            if(isset($this->settings['no_listings_message']) and trim($this->settings['no_listings_message'])) $output = do_shortcode(stripslashes($this->settings['no_listings_message']));
            else $output = $this->alert(esc_html__('No Listing Found!', 'listdom'));
        }

        return $output;
    }

    public function output()
    {
        ob_start();
        include $this->tpl();
        return ob_get_clean();
    }

    public function get_skins()
    {
        return array(
            'singlemap'=>esc_html__('Single Map', 'listdom'),
            'list'=>esc_html__('List View', 'listdom'),
            'grid'=>esc_html__('Grid View', 'listdom'),
            'listgrid'=>esc_html__('List+Grid View', 'listdom'),
            'halfmap'=>esc_html__('Half Map / Split View', 'listdom'),
            'table'=>esc_html__('Table View', 'listdom'),
            'masonry'=>esc_html__('Masonry View', 'listdom'),
            'carousel'=>esc_html__('Carousel', 'listdom'),
            'slider'=>esc_html__('Slider', 'listdom'),
            'cover'=>esc_html__('Cover View', 'listdom'),
        );
    }

    public function get_search_module()
    {
        global $post;
        return do_shortcode('[listdom-search id="'.$this->sm_shortcode.'" page="'.((is_singular() and $post and isset($post->ID)) ? $post->ID : '').'" shortcode="'.$this->id.'"]');
    }

    public function get_sortbar()
    {
        if(!$this->sortbar) return '';

        ob_start();
        include lsd_template('elements/sortbar.php');
        return ob_get_clean();
    }

    public function get_loadmore_button()
    {
        if(!$this->load_more or ($this->load_more and $this->found_listings <= $this->limit)) return '';

        ob_start();
        include lsd_template('elements/loadmore.php');
        return ob_get_clean();
    }

    public function get_switcher_buttons()
    {
        ob_start();
        include lsd_template('elements/switcher.php');
        return ob_get_clean();
    }

    /**
     * @param LSD_Entity_Listing $listing
     * @return string
     */
    public function get_title_tag($listing)
    {
        $listing_link_method = $this->get_listing_link_method();

        // Link is Enabled
        if(in_array($listing_link_method, array('normal', 'blank'))) return '<a href="'.esc_url(get_the_permalink($listing->post->ID)).'" '.($listing_link_method === 'blank' ? 'target="_blank"' : '').' '.lsd_schema()->url().'>'.LSD_Kses::element($listing->get_title()).'</a>';
        // Link is Disabled
        else return LSD_Kses::element($listing->get_title());
    }

    public function get_listing_link_method()
    {
        return (($this->isPro() and isset($this->skin_options['listing_link']) and trim($this->skin_options['listing_link'])) ? $this->skin_options['listing_link'] : 'normal');
    }

    public function getField($field)
    {
        return isset($this->{$field}) ? $this->{$field} : NULL;
    }

    public function setField($field, $value)
    {
        if(isset($this->{$field})) $this->{$field} = $value;
    }
}

endif;