<?php
// no direct access
defined('ABSPATH') or die();

$singlemap = isset($options['singlemap']) ? $options['singlemap'] : array();
?>
<div class="lsd-form-row lsd-form-row-separator">
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"></div>
    <div class="lsd-col-10">
        <p class="description"><?php echo sprintf(esc_html__('Using %s skin, you can show a simple and clean map of listings in your website.', 'listdom'), '<strong>'.esc_html__('Single Map', 'listdom').'</strong>'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Provider', 'listdom'),
        'for' => 'lsd_display_options_skin_singlemap_map_provider',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::providers(array(
            'id' => 'lsd_display_options_skin_singlemap_map_provider',
            'name' => 'lsd[display][singlemap][map_provider]',
            'value' => (isset($singlemap['map_provider']) ? $singlemap['map_provider'] : LSD_Map_Provider::def()),
            'class' => 'lsd-map-provider-toggle',
            'attributes' => array(
                'data-parent' => '#lsd_skin_display_options_singlemap'
            )
        )); ?>
    </div>
</div>
<div class="lsd-form-row lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Style', 'listdom'),
        'for' => 'lsd_display_options_skin_singlemap_mapstyle',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::mapstyle(array(
            'id' => 'lsd_display_options_skin_singlemap_mapstyle',
            'name' => 'lsd[display][singlemap][mapstyle]',
            'value' => (isset($singlemap['mapstyle']) ? $singlemap['mapstyle'] : '')
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Clustering', 'listdom'),
        'for' => 'lsd_display_options_skin_singlemap_clustering',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_singlemap_clustering',
            'toggle' => '#lsd_display_options_skin_singlemap_clustering_options',
            'name' => 'lsd[display][singlemap][clustering]',
            'value' => (isset($singlemap['clustering']) ? $singlemap['clustering'] : '1')
        )); ?>
    </div>
</div>
<div class="lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
    <div id="lsd_display_options_skin_singlemap_clustering_options" <?php echo ((!isset($singlemap['clustering']) or (isset($singlemap['clustering']) and $singlemap['clustering'])) ? '' : 'style="display: none;"'); ?>>
        <div class="lsd-form-row">
            <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                'title' => esc_html__('Bubbles', 'listdom'),
                'for' => 'lsd_display_options_skin_singlemap_clustering_images',
            )); ?></div>
            <div class="lsd-col-6">
                <?php echo LSD_Form::select(array(
                    'id' => 'lsd_display_options_skin_singlemap_clustering_images',
                    'name' => 'lsd[display][singlemap][clustering_images]',
                    'options' => LSD_Base::get_clustering_icons(),
                    'value' => (isset($singlemap['clustering_images']) ? $singlemap['clustering_images'] : 'img/cluster1/m')
                )); ?>
            </div>
        </div>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Marker/Shape On Click', 'listdom'),
        'for' => 'lsd_display_options_skin_singlemap_mapobject_onclick',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_singlemap_mapobject_onclick',
            'name' => 'lsd[display][singlemap][mapobject_onclick]',
            'options' => array('infowindow'=>esc_html__('Open Infowindow', 'listdom'), 'redirect'=>esc_html__('Redirect to Listing Details Page', 'listdom'), 'lightbox'=>esc_html__('Open Listing Details in Lightbox', 'listdom')),
            'value' => (isset($singlemap['mapobject_onclick']) ? $singlemap['mapobject_onclick'] : 'infowindow')
        )); ?>
        <p class="description"><?php esc_html_e("You can select to show an infowindow when someone clicks on Marker or Shape on the map or open the listing details page directly.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Search', 'listdom'),
        'for' => 'lsd_display_options_skin_singlemap_mapsearch',
    )); ?></div>
    <div class="lsd-col-6">
        <?php if($this->isPro()): ?>
            <?php echo LSD_Form::switcher(array(
                'id' => 'lsd_display_options_skin_singlemap_mapsearch',
                'name' => 'lsd[display][singlemap][mapsearch]',
                'value' => (isset($singlemap['mapsearch']) ? $singlemap['mapsearch'] : '1'),
            )); ?>
            <p class="description"><?php esc_html_e("Provide ability to filter listings based on current map position.", 'listdom'); ?></p>
        <?php else: ?>
            <p class="lsd-alert lsd-warning"><?php echo LSD_Base::missFeatureMessage(esc_html__('Map Search', 'listdom')); ?></p>
        <?php endif; ?>
    </div>
</div>
<?php
    // Action for Third Party Plugins
    do_action('lsd_shortcode_map_options', 'singlemap', $options);
?>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Limit', 'listdom'),
        'for' => 'lsd_display_options_skin_singlemap_limit',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::text(array(
            'id' => 'lsd_display_options_skin_singlemap_limit',
            'name' => 'lsd[display][singlemap][limit]',
            'value' => (isset($singlemap['limit']) ? $singlemap['limit'] : '300')
        )); ?>
        <p class="description"><?php esc_html_e("If you increase the limit to more than 300, then the page may loads pretty slow. We suggest you to use filter options to filter only the listings that you want to show.", 'listdom'); ?></p>
    </div>
</div>