<?php
// no direct access
defined('ABSPATH') or die();

$halfmap = isset($options['halfmap']) ? $options['halfmap'] : array();
?>
<div class="lsd-form-row lsd-form-row-separator">
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"></div>
    <div class="lsd-col-10">
        <p class="description"><?php echo sprintf(esc_html__('Using %s skin, you can show a list + grid view of the listings next to a map.', 'listdom'), '<strong>'.esc_html__('Half Map', 'listdom').'</strong>'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Style', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_style',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_halfmap_style',
            'name' => 'lsd[display][halfmap][style]',
            'options' => LSD_Styles::halfmap(),
            'value' => (isset($halfmap['style']) ? $halfmap['style'] : 'style1')
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Provider', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_map_provider',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::providers(array(
            'id' => 'lsd_display_options_skin_halfmap_map_provider',
            'name' => 'lsd[display][halfmap][map_provider]',
            'value' => (isset($halfmap['map_provider']) ? $halfmap['map_provider'] : LSD_Map_Provider::def()),
            'class' => 'lsd-map-provider-toggle',
            'attributes' => array(
                'data-parent' => '#lsd_skin_display_options_halfmap'
            )
        )); ?>
    </div>
</div>
<div class="lsd-form-row lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Style', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_mapstyle',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::mapstyle(array(
            'id' => 'lsd_display_options_skin_halfmap_mapstyle',
            'name' => 'lsd[display][halfmap][mapstyle]',
            'value' => (isset($halfmap['mapstyle']) ? $halfmap['mapstyle'] : '')
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Clustering', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_clustering',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_halfmap_clustering',
            'toggle' => '#lsd_display_options_skin_halfmap_clustering_options',
            'name' => 'lsd[display][halfmap][clustering]',
            'value' => (isset($halfmap['clustering']) ? $halfmap['clustering'] : '1')
        )); ?>
    </div>
</div>
<div class="lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
    <div id="lsd_display_options_skin_halfmap_clustering_options" <?php echo ((!isset($halfmap['clustering']) or (isset($halfmap['clustering']) and $halfmap['clustering'])) ? '' : 'style="display: none;"'); ?>>
        <div class="lsd-form-row">
            <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                'title' => esc_html__('Bubbles', 'listdom'),
                'for' => 'lsd_display_options_skin_halfmap_clustering_images',
            )); ?></div>
            <div class="lsd-col-6">
                <?php echo LSD_Form::select(array(
                    'id' => 'lsd_display_options_skin_halfmap_clustering_images',
                    'name' => 'lsd[display][halfmap][clustering_images]',
                    'options' => LSD_Base::get_clustering_icons(),
                    'value' => (isset($halfmap['clustering_images']) ? $halfmap['clustering_images'] : 'img/cluster1/m')
                )); ?>
            </div>
        </div>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Marker/Shape On Click', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_mapobject_onclick',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_halfmap_mapobject_onclick',
            'name' => 'lsd[display][halfmap][mapobject_onclick]',
            'options' => array('infowindow'=>esc_html__('Open Infowindow', 'listdom'), 'redirect'=>esc_html__('Redirect to Listing Details Page', 'listdom'), 'lightbox'=>esc_html__('Open Listing Details in Lightbox', 'listdom')),
            'value' => (isset($halfmap['mapobject_onclick']) ? $halfmap['mapobject_onclick'] : 'infowindow')
        )); ?>
        <p class="description"><?php esc_html_e("You can select to show an infowindow when someone clicks on Marker or Shape on the map or open the listing details page directly.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Search', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_mapsearch',
    )); ?></div>
    <div class="lsd-col-6">
        <?php if($this->isPro()): ?>
            <?php echo LSD_Form::switcher(array(
                'id' => 'lsd_display_options_skin_halfmap_mapsearch',
                'name' => 'lsd[display][halfmap][mapsearch]',
                'value' => (isset($halfmap['mapsearch']) ? $halfmap['mapsearch'] : '1'),
            )); ?>
            <p class="description"><?php esc_html_e("Provide ability to filter listings based on current map position.", 'listdom'); ?></p>
        <?php else: ?>
            <p class="lsd-alert lsd-warning"><?php echo LSD_Base::missFeatureMessage(esc_html__('Map Search', 'listdom')); ?></p>
        <?php endif; ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Limit', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_maplimit',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::text(array(
            'id' => 'lsd_display_options_skin_halfmap_maplimit',
            'name' => 'lsd[display][halfmap][maplimit]',
            'value' => (isset($halfmap['maplimit']) ? $halfmap['maplimit'] : '300')
        )); ?>
        <p class="description"><?php esc_html_e("It's for Map. If you increase the limit to more than 300, then the page may loads pretty slow. We suggest you to use filter options to filter only the listings that you want to show.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Position', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_map_position',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_halfmap_map_position',
            'name' => 'lsd[display][halfmap][map_position]',
            'options' => array('left'=>esc_html__('Left', 'listdom'), 'right'=>esc_html__('Right', 'listdom')),
            'value' => (isset($halfmap['map_position']) ? $halfmap['map_position'] : 'left')
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Height', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_map_height',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::text(array(
            'id' => 'lsd_display_options_skin_halfmap_map_height',
            'name' => 'lsd[display][halfmap][map_height]',
            'value' => (isset($halfmap['map_height']) ? $halfmap['map_height'] : '500')
        )); ?>
        <p class="description"><?php esc_html_e("Maps height in pixels. Don't insert any unit like px, etc.", 'listdom'); ?></p>
    </div>
</div>

<?php
    // Action for Third Party Plugins
    do_action('lsd_shortcode_map_options', 'halfmap', $options);
?>

<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Listings Per Row', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_columns',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_halfmap_columns',
            'name' => 'lsd[display][halfmap][columns]',
            'options' => array('2'=>2, '3'=>3, '4'=>4),
            'value' => (isset($halfmap['columns']) ? $halfmap['columns'] : '2')
        )); ?>
        <p class="description"><?php esc_html_e("It used for grid view.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Limit', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_limit',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::text(array(
            'id' => 'lsd_display_options_skin_halfmap_limit',
            'name' => 'lsd[display][halfmap][limit]',
            'value' => (isset($halfmap['limit']) ? $halfmap['limit'] : '12')
        )); ?>
        <p class="description"><?php echo sprintf(esc_html__("Number of Listings Per Page. It should be multiply of %s option. For example if %s is set to 3, you should set the limit to 3, 6, 9, 12, 30, etc.", 'listdom'), '<strong>'.esc_html__('Listings Per Row', 'listdom').'</strong>', '<strong>'.esc_html__('Listings Per Row', 'listdom').'</strong>'); ?></p>
    </div>
</div>

<?php if($this->isPro()): ?>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Listing Link', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_listing_link',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_halfmap_listing_link',
            'name' => 'lsd[display][halfmap][listing_link]',
            'value' => (isset($halfmap['listing_link']) ? $halfmap['listing_link'] : 'normal'),
            'options' => array(
                'normal' => esc_html__('Same Window', 'listdom'),
                'blank' => esc_html__('New Window', 'listdom'),
                'disabled' => esc_html__('Disabled', 'listdom'),
            ),
        )); ?>
        <p class="description"><?php esc_html_e("Link to listing detail page.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Display Image', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_display_image',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_halfmap_display_image',
            'name' => 'lsd[display][halfmap][display_image]',
            'value' => (isset($halfmap['display_image']) ? $halfmap['display_image'] : '1')
        )); ?>
        <p class="description"><?php esc_html_e("Display listing image.", 'listdom'); ?></p>
    </div>
</div>
<?php else: ?>
<div class="lsd-form-row">
    <div class="lsd-col-2">
    </div>
    <div class="lsd-col-6">
        <p class="lsd-alert lsd-warning lsd-mt-0"><?php echo LSD_Base::missFeatureMessage(esc_html__('Listing Link & Display Image', 'listdom'), true); ?></p>
    </div>
</div>
<?php endif; ?>

<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Load More', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_load_more',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_halfmap_load_more',
            'name' => 'lsd[display][halfmap][load_more]',
            'value' => (isset($halfmap['load_more']) ? $halfmap['load_more'] : '1')
        )); ?>
        <p class="description"><?php esc_html_e("This is for loading new listings into the page.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Display Labels', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_display_labels',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_halfmap_display_labels',
            'name' => 'lsd[display][halfmap][display_labels]',
            'value' => (isset($halfmap['display_labels']) ? $halfmap['display_labels'] : '0')
        )); ?>
        <p class="description"><?php esc_html_e("Display listing labels on the image or not.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Display Share Buttons', 'listdom'),
        'for' => 'lsd_display_options_skin_halfmap_display_share_buttons',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_halfmap_display_share_buttons',
            'name' => 'lsd[display][halfmap][display_share_buttons]',
            'value' => (isset($halfmap['display_share_buttons']) ? $halfmap['display_share_buttons'] : '0')
        )); ?>
        <p class="description"><?php esc_html_e("Display share buttons.", 'listdom'); ?></p>
    </div>
</div>