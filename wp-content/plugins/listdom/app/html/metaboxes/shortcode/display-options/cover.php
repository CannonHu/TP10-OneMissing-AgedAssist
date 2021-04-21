<?php
// no direct access
defined('ABSPATH') or die();

$cover = isset($options['cover']) ? $options['cover'] : array();
?>
<div class="lsd-form-row lsd-form-row-separator">
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"></div>
    <div class="lsd-col-10">
        <p class="description"><?php echo sprintf(esc_html__("Using %s skin, you can show only 1 listing in a nice style. You can use multiple cover shortcode in one page to show more listings.", 'listdom'), '<strong>'.esc_html__('Cover', 'listdom').'</strong>'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Style', 'listdom'),
        'for' => 'lsd_display_options_skin_cover_style',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_cover_style',
            'name' => 'lsd[display][cover][style]',
            'options' => LSD_Styles::cover(),
            'value' => (isset($cover['style']) ? $cover['style'] : 'style1')
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Listing', 'listdom'),
        'for' => 'lsd_display_options_skin_cover_listing',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::listings(array(
            'id' => 'lsd_display_options_skin_cover_listing',
            'name' => 'lsd[display][cover][listing]',
            'value' => (isset($cover['listing']) ? $cover['listing'] : NULL),
            'has_post_thumbnail' => true
        )); ?>
        <p class="description"><?php echo esc_html__("You can select only listings with featured image here!", 'listdom'); ?></p>
    </div>
</div>

<?php if($this->isPro()): ?>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Listing Link', 'listdom'),
        'for' => 'lsd_display_options_skin_cover_listing_link',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_cover_listing_link',
            'name' => 'lsd[display][cover][listing_link]',
            'value' => (isset($cover['listing_link']) ? $cover['listing_link'] : 'normal'),
            'options' => array(
                'normal' => esc_html__('Same Window', 'listdom'),
                'blank' => esc_html__('New Window', 'listdom'),
                'disabled' => esc_html__('Disabled', 'listdom'),
            ),
        )); ?>
        <p class="description"><?php esc_html_e("Link to listing detail page.", 'listdom'); ?></p>
    </div>
</div>
<?php else: ?>
<div class="lsd-form-row">
    <div class="lsd-col-2">
    </div>
    <div class="lsd-col-6">
        <p class="lsd-alert lsd-warning lsd-mt-0"><?php echo LSD_Base::missFeatureMessage(esc_html__('Listing Link', 'listdom')); ?></p>
    </div>
</div>
<?php endif; ?>