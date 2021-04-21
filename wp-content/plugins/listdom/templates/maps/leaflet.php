<?php
// no direct access
defined('ABSPATH') or die();

// Include Leaflet Assets to the page
$assets = new LSD_Assets();
$assets->leaflet();

// Listdom Settings
$settings = LSD_Options::settings();

$latitude = isset($args['default_lt']) ? $args['default_lt'] : $settings['map_backend_lt'];
$longitude = isset($args['default_ln']) ? $args['default_ln'] : $settings['map_backend_ln'];
$style = isset($args['mapstyle']) ? $args['mapstyle'] : NULL;
$zoomlevel = isset($args['zoomlevel']) ? $args['zoomlevel'] : 14;
$gps_zl = isset($settings['map_gps_zl']) ? $settings['map_gps_zl'] : 13;
$gps_zl_current = isset($settings['map_gps_zl_current']) ? $settings['map_gps_zl_current'] : 7;
$canvas_height = isset($args['canvas_height']) ? $args['canvas_height'] : NULL;
$atts = (isset($args['atts']) and is_array($args['atts'])) ? $args['atts'] : array();
$mapsearch = (isset($args['mapsearch']) and $args['mapsearch']) ? true : false;
$gplaces = (isset($args['gplaces']) and $args['gplaces']) ? true : false;
$max_bounds = (isset($args['max_bounds']) and is_array($args['max_bounds'])) ? $args['max_bounds'] : array();
$access_token = LSD_Options::mapbox_token();

// The Unique ID
$id = isset($args['id']) ? $args['id'] : mt_rand(100, 999);

if(isset($args['objects']) and is_array($args['objects']))
{
    $objects = $args['objects'];
}
else
{
    $archive = new LSD_PTypes_Listing_Archive();
    $objects = $archive->render_map_objects($listings, $args);
}

// Add Leaflet JS codes to footer
$assets->footer('<script>
jQuery(document).ready(function()
{
    jQuery("#lsd_map'.$id.'").listdomLeaflet(
    {
        latitude: "'.$latitude.'",
        longitude: "'.$longitude.'",
        id: '.$id.',
        ajax_url: "'.admin_url('admin-ajax.php', NULL).'",
        zoom: '.$zoomlevel.',
        objects: '.json_encode($objects, JSON_NUMERIC_CHECK).',
        args: "'.http_build_query(array('args'=>$args), '', '&').'",
        richmarker: "",
        infobox: "",
        clustering: '.((isset($args['clustering']) and $args['clustering']) ? 'true' : 'false').',
        clustering_images: "'.$assets->lsd_asset_url(((isset($args['clustering_images']) and trim($args['clustering_images'])) ? $args['clustering_images'] : 'img/cluster1/m')).'",
        styles: "",
        mapcontrols: "",
        fill_color: "'.$settings['map_shape_fill_color'].'",
        fill_opacity: '.$settings['map_shape_fill_opacity'].',
        stroke_color: "'.$settings['map_shape_stroke_color'].'",
        stroke_opacity: '.$settings['map_shape_stroke_opacity'].',
        stroke_weight: '.$settings['map_shape_stroke_weight'].',
        atts: "'.http_build_query(array('atts'=>$atts), '', '&').'",
        mapsearch: '.($mapsearch ? 'true' : 'false').',
        gplaces: false,
        max_bounds: '.json_encode($max_bounds, JSON_NUMERIC_CHECK).',
        gps_zoom: {
            zl: '.$gps_zl.',
            current: '.$gps_zl_current.'
        },
        access_token: "'.$access_token.'",
        tileserver: '.apply_filters('lsd_leaflet_tileserver', '""').',
        layers: '.json_encode(apply_filters('lsd_map_layers', array(), LSD_MP_LEAFLET), JSON_NUMERIC_CHECK).',
    });
});
</script>');
?>
<div class="lsd-listing-leaflet">
    <div id="lsd_map<?php echo esc_attr($id); ?>" class="<?php echo (isset($args['canvas_class']) ? $args['canvas_class'] : 'lsd-map-canvas'); ?>" <?php if($canvas_height) echo 'style="height: '.esc_attr($canvas_height).'px;"'; ?>></div>
</div>