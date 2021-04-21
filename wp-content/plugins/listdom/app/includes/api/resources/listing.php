<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API_Resources_Listing')):

/**
 * Listdom API Listing Resource Class.
 *
 * @class LSD_API_Resources_Listing
 * @version	1.0.0
 */
class LSD_API_Resources_Listing extends LSD_API_Resource
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

        // Listing
        $listing = get_post($id);

        // Meta Values
        $metas = $resource->get_post_meta($id);

        $min_price = (isset($metas['lsd_price']) ? $metas['lsd_price'] : NULL);
        $max_price = (isset($metas['lsd_price_max']) ? $metas['lsd_price_max'] : NULL);
        $text_price = (isset($metas['lsd_price_after']) ? $metas['lsd_price_after'] : NULL);
        $currency = (isset($metas['lsd_currency']) ? $metas['lsd_currency'] : NULL);

        $price = $resource->render_price($min_price, $currency);
        if($max_price) $price .= ' - '.$resource->render_price($max_price, $currency);
        if($text_price) $price .= ' / '.$text_price;

        // Media
        $thumbnail_id = get_post_thumbnail_id($listing);
        $gallery = ((isset($metas['lsd_gallery']) and is_array($metas['lsd_gallery'])) ? $metas['lsd_gallery'] : array());
        $embeds = ((isset($metas['lsd_embeds']) and is_array($metas['lsd_embeds'])) ? $metas['lsd_embeds'] : array());

        $status = get_post_status_object($listing->post_status);

        return apply_filters('lsd_api_resource_listing', array(
            'id' => $listing->ID,
            'data' => array(
                'ID' => $listing->ID,
                'title' => get_the_title($listing),
                'content' => apply_filters('the_content', $listing->post_content),
                'excerpt' => $listing->post_excerpt,
                'date' => $listing->post_date,
                'status' => array(
                    'key' => $listing->post_status,
                    'label' => $status->label,
                ),
                'address' => (isset($metas['lsd_address']) ? $metas['lsd_address'] : NULL),
                'price' => $price,
                'latitude' => (isset($metas['lsd_latitude']) ? $metas['lsd_latitude'] : NULL),
                'longitude' => (isset($metas['lsd_longitude']) ? $metas['lsd_longitude'] : NULL),
                'zoomlevel' => (isset($metas['lsd_zoomlevel']) ? $metas['lsd_zoomlevel'] : NULL),
                'object_type' => (isset($metas['lsd_object_type']) ? $metas['lsd_object_type'] : 'marker'),
                'shape_type' => (isset($metas['lsd_shape_type']) ? $metas['lsd_shape_type'] : NULL),
                'shape_paths' => (isset($metas['lsd_shape_paths']) ? $metas['lsd_shape_paths'] : NULL),
                'shape_radius' => (isset($metas['lsd_shape_radius']) ? $metas['lsd_shape_radius'] : NULL),
                'link' => (isset($metas['lsd_link']) ? $metas['lsd_link'] : NULL),
                'email' => (isset($metas['lsd_email']) ? $metas['lsd_email'] : NULL),
                'phone' => (isset($metas['lsd_phone']) ? $metas['lsd_phone'] : NULL),
                'remark' => (isset($metas['lsd_remark']) ? $metas['lsd_remark'] : NULL),
                'availability' => LSD_API_Resources_Availability::get($listing->ID),
                'favorite' => apply_filters('lsd_is_favorite', 0, $listing->ID),
                'claimed' => apply_filters('lsd_is_claimed', 0, $listing->ID),
            ),
            'raw' => array(
                'content' => strip_tags(apply_filters('the_content', $listing->post_content)),
                'price' => $min_price,
                'price_max' => $max_price,
                'price_after' => $text_price,
                'currency' => $currency,
                'featured_image' => $thumbnail_id,
                'gallery' => $gallery,
            ),
            'taxonomies' => LSD_API_Resources_Taxonomy::listing($listing->ID),
            'attributes' => LSD_API_Resources_Attribute::listing($listing->ID),
            'media' => array(
                'featured_image' => LSD_API_Resources_Image::get($thumbnail_id),
                'gallery' => LSD_API_Resources_Image::collection($gallery),
                'embed' => LSD_API_Resources_Embed::collection($embeds),
            ),
            'user' => LSD_API_Resources_User::minify($listing->post_author),
        ), $id);
	}

    public static function collection($ids)
    {
        $items = array();
        foreach($ids as $id) $items[] = self::get($id);

        return $items;
    }

    public static function minify($id)
    {
        // Listing
        $listing = get_post($id);

        // Featured Image
        $thumbnail_id = get_post_thumbnail_id($listing);

        // Listing Status
        $status = get_post_status_object($listing->post_status);

        return apply_filters('lsd_api_resource_listing', array(
            'id' => $listing->ID,
            'data' => array(
                'ID' => $listing->ID,
                'title' => get_the_title($listing),
                'status' => array(
                    'key' => $listing->post_status,
                    'label' => $status->label,
                ),
            ),
            'media' => array(
                'featured_image' => LSD_API_Resources_Image::get($thumbnail_id),
            ),
        ), $id);
    }

    public static function pagination($skin)
    {
        $found = $skin->found_listings;

        $total_pages = ceil(($found / $skin->limit));
        $current_page = ($skin->next_page - 1);
        $previous_page = ($current_page - 1);
        $next_page = $skin->next_page;

        if($current_page >= $total_pages) $next_page = NULL;
        if($current_page <= 1) $previous_page = NULL;

        return apply_filters('lsd_api_resource_pagination', array(
            'found_listings' => $found,
            'listings_per_page' => $skin->limit,
            'total_pages' => $total_pages,
            'previous_page' => $previous_page,
            'current_page' => $current_page,
            'next_page' => $next_page,
        ), $skin);
    }
}

endif;