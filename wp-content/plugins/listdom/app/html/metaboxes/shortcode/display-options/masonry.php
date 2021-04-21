<?php
// no direct access
defined('ABSPATH') or die();

$masonry = isset($options['masonry']) ? $options['masonry'] : array();
?>
<div class="lsd-form-row lsd-form-row-separator">
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"></div>
    <div class="lsd-col-10">
        <p class="description"><?php echo sprintf(esc_html__('Using %s skin, you can show a grid view of the listings with filtering options on top.', 'listdom'), '<strong>'.esc_html__('Masonry', 'listdom').'</strong>'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Style', 'listdom'),
        'for' => 'lsd_display_options_skin_masonry_style',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_masonry_style',
            'name' => 'lsd[display][masonry][style]',
            'options' => LSD_Styles::masonry(),
            'value' => (isset($masonry['style']) ? $masonry['style'] : 'style1')
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Filter By', 'listdom'),
        'for' => 'lsd_display_options_skin_masonry_filter_by',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_masonry_filter_by',
            'name' => 'lsd[display][masonry][filter_by]',
            'options' => array(
                LSD_Base::TAX_CATEGORY=>esc_html__('Categories', 'listdom'),
                LSD_Base::TAX_LOCATION=>esc_html__('Locations', 'listdom'),
                LSD_Base::TAX_FEATURE=>esc_html__('Features', 'listdom'),
                LSD_Base::TAX_LABEL=>esc_html__('Labels', 'listdom'),
            ),
            'value' => (isset($masonry['filter_by']) ? $masonry['filter_by'] : LSD_Base::TAX_CATEGORY)
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Listings Per Row', 'listdom'),
        'for' => 'lsd_display_options_skin_masonry_columns',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_masonry_columns',
            'name' => 'lsd[display][masonry][columns]',
            'options' => array('2'=>2, '3'=>3, '4'=>4, '6'=>6),
            'value' => (isset($masonry['columns']) ? $masonry['columns'] : '3')
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Limit', 'listdom'),
        'for' => 'lsd_display_options_skin_masonry_limit',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::text(array(
            'id' => 'lsd_display_options_skin_masonry_limit',
            'name' => 'lsd[display][masonry][limit]',
            'value' => (isset($masonry['limit']) ? $masonry['limit'] : '12')
        )); ?>
        <p class="description"><?php echo sprintf(esc_html__("Number of Listings Per Page. It should be multiply of %s option. For example if %s is set to 3, you should set the limit to 3, 6, 9, 12, 30, etc.", 'listdom'), '<strong>'.esc_html__('Listings Per Row', 'listdom').'</strong>', '<strong>'.esc_html__('Listings Per Row', 'listdom').'</strong>'); ?></p>
    </div>
</div>

<?php if($this->isPro()): ?>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Listing Link', 'listdom'),
        'for' => 'lsd_display_options_skin_masonry_listing_link',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_masonry_listing_link',
            'name' => 'lsd[display][masonry][listing_link]',
            'value' => (isset($masonry['listing_link']) ? $masonry['listing_link'] : 'normal'),
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
        'for' => 'lsd_display_options_skin_masonry_display_image',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_masonry_display_image',
            'name' => 'lsd[display][masonry][display_image]',
            'value' => (isset($masonry['display_image']) ? $masonry['display_image'] : '1')
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
        'title' => esc_html__('Display Labels', 'listdom'),
        'for' => 'lsd_display_options_skin_masonry_display_labels',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_masonry_display_labels',
            'name' => 'lsd[display][masonry][display_labels]',
            'value' => (isset($masonry['display_labels']) ? $masonry['display_labels'] : '0')
        )); ?>
        <p class="description"><?php esc_html_e("Display listing labels on the image or not.", 'listdom'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Display Share Buttons', 'listdom'),
        'for' => 'lsd_display_options_skin_masonry_display_share_buttons',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_masonry_display_share_buttons',
            'name' => 'lsd[display][masonry][display_share_buttons]',
            'value' => (isset($masonry['display_share_buttons']) ? $masonry['display_share_buttons'] : '0')
        )); ?>
        <p class="description"><?php esc_html_e("Display share buttons.", 'listdom'); ?></p>
    </div>
</div>