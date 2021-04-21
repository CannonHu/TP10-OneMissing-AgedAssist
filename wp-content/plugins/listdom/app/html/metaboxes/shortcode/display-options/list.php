<?php
// no direct access
defined('ABSPATH') or die();

$list = isset($options['list']) ? $options['list'] : array();
?>
<div class="lsd-form-row lsd-form-row-separator">
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"></div>
    <div class="lsd-col-10">
        <p class="description"><?php echo sprintf(esc_html__('Using %s skin, you can show a list view of the listings. You can include Google Maps to the list view as well.', 'listdom'), '<strong>'.esc_html__('List', 'listdom').'</strong>'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Style', 'listdom'),
        'for' => 'lsd_display_options_skin_list_style',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_list_style',
            'name' => 'lsd[display][list][style]',
            'options' => LSD_Styles::listSkin(),
            'value' => (isset($list['style']) ? $list['style'] : 'style1')
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Map Provider', 'listdom'),
        'for' => 'lsd_display_options_skin_list_map_provider',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::providers(array(
            'id' => 'lsd_display_options_skin_list_map_provider',
            'name' => 'lsd[display][list][map_provider]',
            'value' => (isset($list['map_provider']) ? $list['map_provider'] : LSD_Map_Provider::def()),
            'disabled' => true,
            'class' => 'lsd-map-provider-toggle',
            'attributes' => array(
                'data-parent' => '#lsd_skin_display_options_list'
            )
        )); ?>
    </div>
</div>
<div class="lsd-form-group lsd-form-row-map-needed <?php echo ((isset($list['map_provider']) and $list['map_provider']) ? '' : 'lsd-util-hide'); ?>" id="lsd_display_options_skin_list_map_options">
    <div class="lsd-form-row">
        <div class="lsd-col-2"><?php echo LSD_Form::label(array(
            'title' => esc_html__('Position', 'listdom'),
            'for' => 'lsd_display_options_skin_list_map_position',
        )); ?></div>
        <div class="lsd-col-6">
            <?php echo LSD_Form::select(array(
                'id' => 'lsd_display_options_skin_list_map_position',
                'name' => 'lsd[display][list][map_position]',
                'options' => array('top'=>esc_html__('Show before list view', 'listdom'), 'bottom'=>esc_html__('Show after list view', 'listdom')),
                'value' => (isset($list['map_position']) ? $list['map_position'] : 'top')
            )); ?>
        </div>
    </div>
    <div class="lsd-form-row lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
        <div class="lsd-col-2"><?php echo LSD_Form::label(array(
            'title' => esc_html__('Map Style', 'listdom'),
            'for' => 'lsd_display_options_skin_list_mapstyle',
        )); ?></div>
        <div class="lsd-col-6">
            <?php echo LSD_Form::mapstyle(array(
                'id' => 'lsd_display_options_skin_list_mapstyle',
                'name' => 'lsd[display][list][mapstyle]',
                'value' => (isset($list['mapstyle']) ? $list['mapstyle'] : '')
            )); ?>
        </div>
    </div>
    <div class="lsd-form-row">
        <div class="lsd-col-2"><?php echo LSD_Form::label(array(
            'title' => esc_html__('Clustering', 'listdom'),
            'for' => 'lsd_display_options_skin_list_clustering',
        )); ?></div>
        <div class="lsd-col-6">
            <?php echo LSD_Form::switcher(array(
                'id' => 'lsd_display_options_skin_list_clustering',
                'toggle' => '#lsd_display_options_skin_list_clustering_options',
                'name' => 'lsd[display][list][clustering]',
                'value' => (isset($list['clustering']) ? $list['clustering'] : '1')
            )); ?>
        </div>
    </div>
    <div class="lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
        <div id="lsd_display_options_skin_list_clustering_options" <?php echo ((!isset($list['clustering']) or (isset($list['clustering']) and $list['clustering'])) ? '' : 'style="display: none;"'); ?>>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                        'title' => esc_html__('Bubbles', 'listdom'),
                        'for' => 'lsd_display_options_skin_list_clustering_images',
                    )); ?></div>
                <div class="lsd-col-6">
                    <?php echo LSD_Form::select(array(
                        'id' => 'lsd_display_options_skin_list_clustering_images',
                        'name' => 'lsd[display][list][clustering_images]',
                        'options' => LSD_Base::get_clustering_icons(),
                        'value' => (isset($list['clustering_images']) ? $list['clustering_images'] : 'img/cluster1/m')
                    )); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="lsd-form-row">
        <div class="lsd-col-2"><?php echo LSD_Form::label(array(
            'title' => esc_html__('Marker/Shape On Click', 'listdom'),
            'for' => 'lsd_display_options_skin_list_mapobject_onclick',
        )); ?></div>
        <div class="lsd-col-6">
            <?php echo LSD_Form::select(array(
                'id' => 'lsd_display_options_skin_list_mapobject_onclick',
                'name' => 'lsd[display][list][mapobject_onclick]',
                'options' => array('infowindow'=>esc_html__('Open Infowindow', 'listdom'), 'redirect'=>esc_html__('Redirect to Listing Details Page', 'listdom'), 'lightbox'=>esc_html__('Open Listing Details in Lightbox', 'listdom')),
                'value' => (isset($list['mapobject_onclick']) ? $list['mapobject_onclick'] : 'infowindow')
            )); ?>
            <p class="description"><?php esc_html_e("You can select to show an infowindow when someone clicks on Marker or Shape on the map or open the listing details page directly.", 'listdom'); ?></p>
        </div>
    </div>
    <div class="lsd-form-row lsd-map-provider-dependency lsd-map-provider-dependency-googlemap">
        <div class="lsd-col-2"><?php echo LSD_Form::label(array(
            'title' => esc_html__('Map Search', 'listdom'),
            'for' => 'lsd_display_options_skin_list_mapsearch',
        )); ?></div>
        <div class="lsd-col-6">
            <?php if($this->isPro()): ?>
                <?php echo LSD_Form::switcher(array(
                    'id' => 'lsd_display_options_skin_list_mapsearch',
                    'name' => 'lsd[display][list][mapsearch]',
                    'value' => (isset($list['mapsearch']) ? $list['mapsearch'] : '1'),
                )); ?>
                <p class="description"><?php esc_html_e("Provide ability to filter listings based on current map position.", 'listdom'); ?></p>
            <?php else: ?>
                <p class="lsd-alert lsd-warning lsd-mt-0"><?php echo LSD_Base::missFeatureMessage(esc_html__('Map Search', 'listdom')); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="lsd-form-row">
        <div class="lsd-col-2"><?php echo LSD_Form::label(array(
            'title' => esc_html__('Map Limit', 'listdom'),
            'for' => 'lsd_display_options_skin_list_maplimit',
        )); ?></div>
        <div class="lsd-col-6">
            <?php echo LSD_Form::text(array(
                'id' => 'lsd_display_options_skin_list_maplimit',
                'name' => 'lsd[display][list][maplimit]',
                'value' => (isset($list['maplimit']) ? $list['maplimit'] : '300')
            )); ?>
            <p class="description"><?php esc_html_e("It's for Map. If you increase the limit to more than 300, then the page may loads pretty slow. We suggest you to use filter options to filter only the listings that you want to show.", 'listdom'); ?></p>
        </div>
    </div>

    <?php
        // Action for Third Party Plugins
        do_action('lsd_shortcode_map_options', 'list', $options);
    ?>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Limit', 'listdom'),
        'for' => 'lsd_display_options_skin_list_limit',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::text(array(
            'id' => 'lsd_display_options_skin_list_limit',
            'name' => 'lsd[display][list][limit]',
            'value' => (isset($list['limit']) ? $list['limit'] : '12')
        )); ?>
        <p class="description"><?php esc_html_e("Number of Listings Per Page", 'listdom'); ?></p>
    </div>
</div>

<?php if($this->isPro()): ?>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Listing Link', 'listdom'),
        'for' => 'lsd_display_options_skin_list_listing_link',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_list_listing_link',
            'name' => 'lsd[display][list][listing_link]',
            'value' => (isset($list['listing_link']) ? $list['listing_link'] : 'normal'),
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
        'for' => 'lsd_display_options_skin_list_display_image',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_list_display_image',
            'name' => 'lsd[display][list][display_image]',
            'value' => (isset($list['display_image']) ? $list['display_image'] : '1')
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
        'for' => 'lsd_display_options_skin_list_load_more',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_list_load_more',
            'name' => 'lsd[display][list][load_more]',
            'value' => (isset($list['load_more']) ? $list['load_more'] : '1')
        )); ?>
        <p class="description"><?php esc_html_e("This is for loading new listings into the page.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Display Labels', 'listdom'),
        'for' => 'lsd_display_options_skin_list_display_labels',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_list_display_labels',
            'name' => 'lsd[display][list][display_labels]',
            'value' => (isset($list['display_labels']) ? $list['display_labels'] : '0')
        )); ?>
        <p class="description"><?php esc_html_e("Display listing labels on the image or not.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Display Share Buttons', 'listdom'),
        'for' => 'lsd_display_options_skin_list_display_share_buttons',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_list_display_share_buttons',
            'name' => 'lsd[display][list][display_share_buttons]',
            'value' => (isset($list['display_share_buttons']) ? $list['display_share_buttons'] : '0')
        )); ?>
        <p class="description"><?php esc_html_e("Display share buttons.", 'listdom'); ?></p>
    </div>
</div>