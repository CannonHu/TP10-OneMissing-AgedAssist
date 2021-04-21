<?php
// no direct access
defined('ABSPATH') or die();

$styles = LSD_Options::styles();
?>
<div class="lsd-settings-wrap">
    <h3><?php esc_html_e('Custom Styles', 'listdom'); ?></h3>
    <form id="lsd_settings_form">
        <div class="lsd-form-row">
            <div class="lsd-col-12">
                <?php echo LSD_Form::textarea(array(
                    'id' => 'lsd_settings_custom_styles',
                    'name' => 'lsd[CSS]',
                    'value' => (isset($styles['CSS']) ? $styles['CSS'] : '')
                )); ?>
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
        data: "action=lsd_save_styles&" + settings,
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