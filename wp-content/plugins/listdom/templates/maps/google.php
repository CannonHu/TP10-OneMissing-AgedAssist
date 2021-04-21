<?php
// no direct access
defined('ABSPATH') or die();

// Include Google maps Assets to the page
$assets = new LSD_Assets();
$assets->googlemaps();

// Listdom Settings
$settings = LSD_Options::settings();

$latitude = isset($args['default_lt']) ? $args['default_lt'] : $settings['map_backend_lt'];
$longitude = isset($args['default_ln']) ? $args['default_ln'] : $settings['map_backend_ln'];
$style = isset($args['mapstyle']) ? $args['mapstyle'] : NULL;
$zoomlevel = isset($args['zoomlevel']) ? $args['zoomlevel'] : 14;
$canvas_height = isset($args['canvas_height']) ? $args['canvas_height'] : NULL;
$atts = (isset($args['atts']) and is_array($args['atts'])) ? $args['atts'] : array();
$mapsearch = (isset($args['mapsearch']) and $args['mapsearch']) ? true : false;
$autoGPS = (isset($args['autoGPS']) and $args['autoGPS']) ? true : false;
$gps_zl = isset($settings['map_gps_zl']) ? $settings['map_gps_zl'] : 13;
$gps_zl_current = isset($settings['map_gps_zl_current']) ? $settings['map_gps_zl_current'] : 7;
$max_bounds = (isset($args['max_bounds']) and is_array($args['max_bounds'])) ? $args['max_bounds'] : array();
$gplaces = (isset($args['gplaces']) and $args['gplaces']) ? true : false;
$direction = (isset($args['direction']) and $args['direction']) ? true : false;

// Map Controles
$mapcontrols = isset($args['mapcontrols']) ? $args['mapcontrols'] : array();
if(!is_array($mapcontrols) or (is_array($mapcontrols) and !count($mapcontrols))) $mapcontrols = LSD_Options::defaults('mapcontrols');

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

// Add Google Maps JS codes to footer
$assets->footer('<script>
jQuery(document).ready(function()
{
    listdom_add_googlemaps_callbacks(function()
    {
        jQuery("#lsd_map'.$id.'").listdomGoogleMaps(
        {
            latitude: "'.$latitude.'",
            longitude: "'.$longitude.'",
            id: '.$id.',
            ajax_url: "'.admin_url('admin-ajax.php', NULL).'",
            zoom: '.$zoomlevel.',
            objects: '.json_encode($objects, JSON_NUMERIC_CHECK).',
            args: "'.http_build_query(array('args'=>$args), '', '&').'",
            richmarker: "'.$assets->lsd_asset_url('packages/richmarker/richmarker.min.js').'",
            infobox: "'.$assets->lsd_asset_url('js/infobox.min.js').'",
            clustering: '.((isset($args['clustering']) and $args['clustering']) ? '"'.$assets->lsd_asset_url('packages/clusterer/markerclusterer.min.js').'"' : 'false').',
            clustering_images: "'.$assets->lsd_asset_url(((isset($args['clustering_images']) and trim($args['clustering_images'])) ? $args['clustering_images'] : 'img/cluster1/m')).'",
            styles: '.(trim($style) != '' ? $assets->get_googlemap_style($style) : "''").',
            mapcontrols: '.json_encode($mapcontrols, JSON_NUMERIC_CHECK).',
            fill_color: "'.$settings['map_shape_fill_color'].'",
            fill_opacity: '.$settings['map_shape_fill_opacity'].',
            stroke_color: "'.$settings['map_shape_stroke_color'].'",
            stroke_opacity: '.$settings['map_shape_stroke_opacity'].',
            stroke_weight: '.$settings['map_shape_stroke_weight'].',
            atts: "'.http_build_query(array('atts'=>$atts), '', '&').'",
            mapsearch: '.($mapsearch ? 'true' : 'false').',
            autoGPS: '.($autoGPS ? 'true' : 'false').',
            gps_zoom: {
                zl: '.$gps_zl.',
                current: '.$gps_zl_current.'
            },
            max_bounds: '.json_encode($max_bounds, JSON_NUMERIC_CHECK).',
            gplaces: '.($gplaces ? 'true' : 'false').',
            layers: '.json_encode(apply_filters('lsd_map_layers', array(), LSD_MP_GOOGLE), JSON_NUMERIC_CHECK).',
            direction:
            {
                status: '.($direction ? 'true' : 'false').',
                destination:
                {
                    latitude: "'.((isset($objects[0]) and isset($objects[0]['latitude'])) ? $objects[0]['latitude'] : 0).'",
                    longitude: "'.((isset($objects[0]) and isset($objects[0]['longitude'])) ? $objects[0]['longitude'] : 0).'",
                },
                start_marker: "'.apply_filters('lsd_direction_start_icon', $assets->lsd_asset_url('img/markers/green.png')).'",
                end_marker: "'.apply_filters('lsd_direction_end_icon', $assets->lsd_asset_url('img/markers/red.png')).'"
            }
        });
    });
});
</script>');
?>
<div class="lsd-listing-googlemap">
    <div id="lsd_map<?php echo esc_attr($id); ?>" class="<?php echo (isset($args['canvas_class']) ? esc_attr($args['canvas_class']) : 'lsd-map-canvas'); ?>" <?php if($canvas_height) echo 'style="height: '.esc_attr($canvas_height).'px;"'; ?>></div>

    <?php if($direction): ?>
    <div class="lsd-direction">
        <form method="post" action="#" id="lsd_direction_form<?php echo esc_attr($id); ?>">
			<div class="lsd-row">
				<div class="lsd-col-9 lsd-direction-address-wrapper">
					<input class="lsd-direction-address" type="text" placeholder="<?php esc_attr_e('Address from ...', 'listdom') ?>" id="lsd_direction_address<?php echo esc_attr($id); ?>">
					<span class="lsd-direction-reset lsd-util-hide" id="lsd_direction_reset<?php echo esc_attr($id); ?>">X</span>
					<div class="lsd-direction-position-wrapper">
						<input type="hidden" id="lsd_direction_latitude<?php echo esc_attr($id); ?>">
						<input type="hidden" id="lsd_direction_longitude<?php echo esc_attr($id); ?>">
						<span class="lsd-direction-gps" id="lsd_direction_gps<?php echo esc_attr($id); ?>" title="<?php esc_attr_e('Your current location', 'listdom') ?>"><i class="lsd-icon fa fa-location-arrow"></i></span>
					</div>
				</div>
				<div class="lsd-col-3">
					<div class="lsd-direction-button-wrapper">
						<input type="submit" value="<?php esc_html_e('Get Directions', 'listdom'); ?>">
					</div>
				</div>
			</div>
        </form>
    </div>
    <?php endif; ?>

</div>