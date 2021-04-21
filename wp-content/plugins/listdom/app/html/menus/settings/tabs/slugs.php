<?php
// no direct access
defined('ABSPATH') or die();

$settings = LSD_Options::settings();
?>
<div class="lsd-settings-wrap">
    <form id="lsd_settings_form">
        <div class="lsd-settings-form-group">
            <h3><?php esc_html_e('Slugs', 'listdom'); ?></h3>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Listings', 'listdom'),
                    'for' => 'lsd_settings_listings_slug',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::text(array(
                        'id' => 'lsd_settings_listings_slug',
                        'name' => 'lsd[listings_slug]',
                        'value' => (isset($settings['listings_slug']) ? $settings['listings_slug'] : '')
                    )); ?>
                    <p class="description"><?php echo sprintf(esc_html__("It's for changing single page prefix. For example if you set it to markers, then address of one listing will be something like %s on your website.", 'listdom'), 'http://site.com/markers/blah-blah/'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Location', 'listdom'),
                    'for' => 'lsd_settings_location_slug',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::text(array(
                        'id' => 'lsd_settings_location_slug',
                        'name' => 'lsd[location_slug]',
                        'value' => (isset($settings['location_slug']) ? $settings['location_slug'] : '')
                    )); ?>
                    <p class="description"><?php echo esc_html__("It's for changing location archive prefix.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Category', 'listdom'),
                    'for' => 'lsd_settings_category_slug',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::text(array(
                        'id' => 'lsd_settings_category_slug',
                        'name' => 'lsd[category_slug]',
                        'value' => (isset($settings['category_slug']) ? $settings['category_slug'] : '')
                    )); ?>
                    <p class="description"><?php echo esc_html__("It's for changing category archive prefix.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Tag', 'listdom'),
                    'for' => 'lsd_settings_tag_slug',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::text(array(
                        'id' => 'lsd_settings_tag_slug',
                        'name' => 'lsd[tag_slug]',
                        'value' => (isset($settings['tag_slug']) ? $settings['tag_slug'] : '')
                    )); ?>
                    <p class="description"><?php echo esc_html__("It's for changing tag archive prefix.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Feature', 'listdom'),
                    'for' => 'lsd_settings_feature_slug',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::text(array(
                        'id' => 'lsd_settings_feature_slug',
                        'name' => 'lsd[feature_slug]',
                        'value' => (isset($settings['feature_slug']) ? $settings['feature_slug'] : '')
                    )); ?>
                    <p class="description"><?php echo esc_html__("It's for changing feature archive prefix.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Label', 'listdom'),
                    'for' => 'lsd_settings_label_slug',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::text(array(
                        'id' => 'lsd_settings_label_slug',
                        'name' => 'lsd[label_slug]',
                        'value' => (isset($settings['label_slug']) ? $settings['label_slug'] : '')
                    )); ?>
                    <p class="description"><?php echo esc_html__("It's for changing label archive prefix.", 'listdom'); ?></p>
                </div>
            </div>
        </div>
        <div class="lsd-spacer-10"></div>
        <div class="lsd-form-row">
			<div class="lsd-col-12">
				<?php LSD_Form::nonce('lsd_settings_form'); ?>
				<?php echo LSD_Form::submit(array(
					'label' => esc_html__('Save', 'listdom'),
					'id' => 'lsd_settings_save_button',
                    'class' => 'button button-hero button-primary',
				)); ?>
			</div>
        </div>
    </form>
</div>
<script>
jQuery('#lsd_settings_form').on('submit', function(event)
{
    event.preventDefault();

    // Add loading Class to the button
    jQuery("#lsd_settings_save_button").addClass('loading').html('<i class="lsd-icon fa fa-spinner fa-pulse fa-fw"></i>');

    var settings = jQuery("#lsd_settings_form").serialize();
    jQuery.ajax(
    {
        type: "POST",
        url: ajaxurl,
        data: "action=lsd_save_slugs&" + settings,
        success: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_settings_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        },
        error: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_settings_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        }
    });
});
</script>