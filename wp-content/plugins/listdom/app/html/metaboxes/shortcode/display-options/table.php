<?php
// no direct access
defined('ABSPATH') or die();

$table = isset($options['table']) ? $options['table'] : array();
?>
<div class="lsd-form-row lsd-form-row-separator">
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"></div>
    <div class="lsd-col-10">
        <p class="description"><?php echo sprintf(esc_html__("Using %s skin, you can show your desired listings in a clean table. It doesn't show any map.", 'listdom'), '<strong>'.esc_html__('Table', 'listdom').'</strong>'); ?></p>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Style', 'listdom'),
        'for' => 'lsd_display_options_skin_table_style',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_table_style',
            'name' => 'lsd[display][table][style]',
            'options' => LSD_Styles::table(),
            'value' => (isset($table['style']) ? $table['style'] : 'style1')
        )); ?>
    </div>
</div>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Limit', 'listdom'),
        'for' => 'lsd_display_options_skin_table_limit',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::text(array(
            'id' => 'lsd_display_options_skin_table_limit',
            'name' => 'lsd[display][table][limit]',
            'value' => (isset($table['limit']) ? $table['limit'] : '12')
        )); ?>
        <p class="description"><?php esc_html_e("Number of Listings per Page", 'listdom'); ?></p>
    </div>
</div>

<?php if($this->isPro()): ?>
<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Listing Link', 'listdom'),
        'for' => 'lsd_display_options_skin_table_listing_link',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::select(array(
            'id' => 'lsd_display_options_skin_table_listing_link',
            'name' => 'lsd[display][table][listing_link]',
            'value' => (isset($table['listing_link']) ? $table['listing_link'] : 'normal'),
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

<div class="lsd-form-row">
    <div class="lsd-col-2"><?php echo LSD_Form::label(array(
        'title' => esc_html__('Load More', 'listdom'),
        'for' => 'lsd_display_options_skin_table_load_more',
    )); ?></div>
    <div class="lsd-col-6">
        <?php echo LSD_Form::switcher(array(
            'id' => 'lsd_display_options_skin_table_load_more',
            'name' => 'lsd[display][table][load_more]',
            'value' => (isset($table['load_more']) ? $table['load_more'] : '1')
        )); ?>
        <p class="description"><?php esc_html_e("This is for loading new listings into the page.", 'listdom'); ?></p>
    </div>
</div>