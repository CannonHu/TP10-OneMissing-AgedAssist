<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_PTypes_Listing_Archive')):

/**
 * Listdom Listing Post Types Archive Class.
 *
 * @class LSD_PTypes_Listing_Archive
 * @version	1.0.0
 */
class LSD_PTypes_Listing_Archive extends LSD_PTypes_Listing
{
    protected $entity;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        // Call the parent constructor
        parent::__construct();
	}
    
    public function render_map_objects($post_ids = array(), $args = array())
    {
        if(!is_array($post_ids)) return false;

        $objects = array();
        foreach($post_ids as $post_id)
        {
            $object_type = get_post_meta($post_id, 'lsd_object_type', true);

            $entity = new LSD_Entity_Listing($post_id);

            if($object_type == 'marker')
            {
                $object = array();
                $object['type'] = 'marker';
                $object['marker'] = $entity->get_marker();
            }
            else
            {
                $shape_type = get_post_meta($post_id, 'lsd_shape_type', true);
                $shape_paths = get_post_meta($post_id, 'lsd_shape_paths', true);
                $shape_radius = get_post_meta($post_id, 'lsd_shape_radius', true);

                // Get Shape
                $object = LSD_Shape::get(array(
                    'type' => $shape_type,
                    'paths' => $shape_paths,
                    'radius' => $shape_radius,
                ));

                $object['marker'] = NULL;
                $object['fill_color'] = $entity->get_shape_fill_color();
                $object['fill_opacity'] = $entity->get_shape_fill_opacity();
                $object['stroke_color'] = $entity->get_shape_stroke_color();
                $object['stroke_opacity'] = $entity->get_shape_stroke_opacity();
                $object['stroke_weight'] = $entity->get_shape_stroke_weight();
            }

            $object['id'] = $post_id;
            $object['infowindow'] = $entity->get_infowindow();
            $object['latitude'] = get_post_meta($post_id, 'lsd_latitude', true);
            $object['longitude'] = get_post_meta($post_id, 'lsd_longitude', true);
            $object['onclick'] = isset($args['onclick']) ? sanitize_text_field($args['onclick']) : 'infowindow';
            $object['link'] = get_the_permalink($post_id);
            $object['raw'] = trim(get_the_permalink($post_id), '/ ').'/raw';

            // It's required
            $object['lsd'] = array();

            $objects[] = $object;
        }

        return $objects;
    }

    public function do_listings($ids = array(), $column = 'lsd-col-12', $columns = NULL)
    {
        include lsd_template('listings.php');
    }
}

endif;