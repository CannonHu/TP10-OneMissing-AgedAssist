<?php
// no direct access
defined('ABSPATH') or die();

$purchase_code = get_option('lsd_purchase_code', '');
?>
<div class="lsd-activation-wrap">

    <?php if(!LSD_Activation::isValid()): ?>
        <form id="lsd_activation_form">
            <h3><?php esc_html_e('Activation', 'listdom'); ?></h3>
            <div class="lsd-form-row">
                <div class="lsd-col-2"><?php echo LSD_Form::label(array(
                    'title' => esc_html__('Purchase Code', 'listdom'),
                    'for' => 'lsd_activation_purchase_code',
                )); ?></div>
                <div class="lsd-col-4">
                    <?php echo LSD_Form::text(array(
                        'id' => 'lsd_activation_purchase_code',
                        'name' => 'purchase_code',
                        'value' => $purchase_code
                    )); ?>
                    <p class="description"><?php esc_html_e("Purchase code is required for auto update and customer service!", 'listdom'); ?></p>
                </div>
                <div class="lsd-col-1 lsd-text-right">
                    <?php LSD_Form::nonce('lsd_activation_form'); ?>
                    <?php echo LSD_Form::submit(array(
                        'label' => esc_html__('Activate', 'listdom'),
                        'id' => 'lsd_activation_save_button'
                    )); ?>
                </div>
            </div>
            <div class="lsd-form-row">
                <div class="lsd-col-12" id="lsd_activation_alert">
                </div>
            </div>
        </form>
    <?php else: ?>
        <h3 class="lsd-mt-4 lsd-mb-4"><?php echo sprintf(esc_html__('Purchase Code: %s', 'listdom'), '<code>'.get_option('lsd_purchase_code').'</code>'); ?></h3>
        <p class="lsd-alert lsd-success"><?php esc_html_e("This installation is activated so you will receive automatic updates on your website!", 'listdom'); ?></p>
    <?php endif; ?>

</div>
<script>
jQuery('#lsd_activation_form').on('submit', function(event)
{
    event.preventDefault();

    // DOM Elements
    var $alert = jQuery('#lsd_activation_alert');
    var $button = jQuery('#lsd_activation_save_button');

    // Remove Existing Alert
    $alert.removeClass('lsd-error lsd-success lsd-alert').html('');

    // Add loading Class to the button
    $button.addClass('loading').html('<i class="lsd-icon fa fa-spinner fa-pulse fa-fw"></i>');

    var activation = jQuery("#lsd_activation_form").serialize();
    jQuery.ajax(
    {
        type: "POST",
        url: ajaxurl,
        data: "action=lsd_activation&" + activation,
        dataType: "json",
        success: function(response)
        {
            if(response.success)
            {
                $alert.removeClass('lsd-error lsd-success lsd-alert').addClass('lsd-alert lsd-success').html(response.message);
                $button.hide();
            }
            else
            {
                $alert.removeClass('lsd-error lsd-success lsd-alert').addClass('lsd-alert lsd-error').html(response.message);
            }

            // Remove loading Class from the button
            $button.removeClass('loading').html("<?php echo esc_js(esc_attr__('Activate', 'listdom')); ?>");
        },
        error: function()
        {
            // Remove loading Class from the button
            $button.removeClass('loading').html("<?php echo esc_js(esc_attr__('Activate', 'listdom')); ?>");
        }
    });
});
</script>