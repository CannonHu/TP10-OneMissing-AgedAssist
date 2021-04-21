<?php
// no direct access
defined('ABSPATH') or die();
?>
<div class="lsd-settings-wrap">
    <form id="lsd_addons_form">

        <?php do_action('lsd_addon_form'); ?>

        <div class="lsd-spacer-10"></div>
        <div class="lsd-form-row">
			<div class="lsd-col-12">
				<?php LSD_Form::nonce('lsd_addons_form'); ?>
				<?php echo LSD_Form::submit(array(
					'label' => esc_html__('Save', 'listdom'),
					'id' => 'lsd_addons_save_button',
                    'class' => 'button button-hero button-primary',
				)); ?>
			</div>
        </div>
    </form>
</div>
<script>
jQuery('#lsd_addons_form').on('submit', function(event)
{
    event.preventDefault();

    // Add loading Class to the button
    jQuery("#lsd_addons_save_button").addClass('loading').html('<i class="lsd-icon fa fa-spinner fa-pulse fa-fw"></i>');

    var socials = jQuery("#lsd_addons_form").serialize();
    jQuery.ajax(
    {
        type: "POST",
        url: ajaxurl,
        data: "action=lsd_save_addons&" + socials,
        success: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_addons_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        },
        error: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_addons_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        }
    });
});
</script>