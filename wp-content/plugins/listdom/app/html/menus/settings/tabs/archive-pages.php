<?php
// no direct access
defined('ABSPATH') or die();

$settings = LSD_Options::settings();
?>
<div class="lsd-settings-wrap">
    <form id="lsd_settings_form">
        <div class="lsd-settings-form-group">
            <h3><?php esc_html_e('Archive Pages', 'listdom'); ?></h3>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Location', 'listdom'),
                    'for' => 'lsd_settings_location_archive',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::shortcodes(array(
                        'id' => 'lsd_settings_location_archive',
                        'name' => 'lsd[location_archive]',
                        'only_archive_skins' => '1',
                        'show_empty' => '1',
                        'empty_label' => esc_html__('Current Theme Style', 'listdom'),
                        'value' => (isset($settings['location_archive']) ? $settings['location_archive'] : '')
                    )); ?>
                    <p class="description"><?php esc_html_e("If your theme don't support Listdom location template, then Listdom use its own template file which might not be 100% compatible with your theme.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Category', 'listdom'),
                    'for' => 'lsd_settings_category_archive',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::shortcodes(array(
                        'id' => 'lsd_settings_category_archive',
                        'name' => 'lsd[category_archive]',
                        'only_archive_skins' => '1',
                        'show_empty' => '1',
                        'empty_label' => esc_html__('Current Theme Style', 'listdom'),
                        'value' => (isset($settings['category_archive']) ? $settings['category_archive'] : '')
                    )); ?>
                    <p class="description"><?php esc_html_e("If your theme don't support Listdom category template, then Listdom use its own template file which might not be 100% compatible with your theme.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Tag', 'listdom'),
                    'for' => 'lsd_settings_tag_archive',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::shortcodes(array(
                        'id' => 'lsd_settings_tag_archive',
                        'name' => 'lsd[tag_archive]',
                        'only_archive_skins' => '1',
                        'show_empty' => '1',
                        'empty_label' => esc_html__('Current Theme Style', 'listdom'),
                        'value' => (isset($settings['tag_archive']) ? $settings['tag_archive'] : '')
                    )); ?>
                    <p class="description"><?php esc_html_e("If your theme don't support Listdom tag template, then Listdom use its own template file which might not be 100% compatible with your theme.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Feature', 'listdom'),
                    'for' => 'lsd_settings_feature_archive',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::shortcodes(array(
                        'id' => 'lsd_settings_feature_archive',
                        'name' => 'lsd[feature_archive]',
                        'only_archive_skins' => '1',
                        'show_empty' => '1',
                        'empty_label' => esc_html__('Current Theme Style', 'listdom'),
                        'value' => (isset($settings['feature_archive']) ? $settings['feature_archive'] : '')
                    )); ?>
                    <p class="description"><?php esc_html_e("If your theme don't support Listdom feature template, then Listdom use its own template file which might not be 100% compatible with your theme.", 'listdom'); ?></p>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Label', 'listdom'),
                    'for' => 'lsd_settings_label_archive',
                )); ?></div>
                <div class="lsd-col-5">
                    <?php echo LSD_Form::shortcodes(array(
                        'id' => 'lsd_settings_label_archive',
                        'name' => 'lsd[label_archive]',
                        'only_archive_skins' => '1',
                        'show_empty' => '1',
                        'empty_label' => esc_html__('Current Theme Style', 'listdom'),
                        'value' => (isset($settings['label_archive']) ? $settings['label_archive'] : '')
                    )); ?>
                    <p class="description"><?php esc_html_e("If your theme don't support Listdom tag template, then Listdom use its own template file which might not be 100% compatible with your theme.", 'listdom'); ?></p>
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
        data: "action=lsd_save_settings&" + settings,
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