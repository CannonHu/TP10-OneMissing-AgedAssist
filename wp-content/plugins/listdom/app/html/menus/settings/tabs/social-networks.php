<?php
// no direct access
defined('ABSPATH') or die();

// Listdom Social Networks
$SN = new LSD_Socials();
$networks = LSD_Options::socials();
?>
<div class="lsd-settings-wrap">
    <form id="lsd_socials_form">
        <div class="lsd-settings-form-group">
            <h3><?php esc_html_e('Social Networks', 'listdom'); ?></h3>
            <div class="lsd-form-row">
                <div class="lsd-col-2"></div>
                <div class="lsd-col-1"><?php esc_html_e('Profile', 'listdom'); ?></div>
                <div class="lsd-col-1"><?php esc_html_e('Archive Pages', 'listdom'); ?></div>
                <div class="lsd-col-1"><?php esc_html_e('Details Page', 'listdom'); ?></div>
            </div>
            <div class="lsd-social-networks lsd-sortable">
                <?php foreach($networks as $network=>$values): $obj = $SN->get($network, $values); if(!$obj) continue; ?>
                <div class="lsd-form-row lsd-social-network">
                    <div class="lsd-col-1 lsd-text-right lsd-cursor-move lsd-pr-4">
                        <i class="lsd-icon fas fa-arrows-alt"></i>
                    </div>
                    <div class="lsd-col-1 lsd-text-left">
                        <strong><?php echo esc_html($obj->label()); ?></strong>
                        <input type="hidden" name="lsd[<?php echo esc_attr($obj->key()); ?>][key]" value="<?php echo esc_attr($obj->key()); ?>">
                    </div>
                    <div class="lsd-col-1">
                        <label class="lsd-switch">
                            <input type="hidden" name="lsd[<?php echo esc_attr($obj->key()); ?>][profile]" value="0">
                            <input type="checkbox" name="lsd[<?php echo esc_attr($obj->key()); ?>][profile]" value="1" <?php echo ($obj->option('profile') == 1 ? 'checked="checked"' : ''); ?>>
                            <span class="lsd-slider"></span>
                        </label>
                    </div>
                    <div class="lsd-col-1">
                        <label class="lsd-switch">
                            <input type="hidden" name="lsd[<?php echo esc_attr($obj->key()); ?>][archive_share]" value="0">
                            <input type="checkbox" name="lsd[<?php echo esc_attr($obj->key()); ?>][archive_share]" value="1" <?php echo ($obj->option('archive_share') == 1 ? 'checked="checked"' : ''); ?>>
                            <span class="lsd-slider"></span>
                        </label>
                    </div>
                    <div class="lsd-col-1">
                        <label class="lsd-switch">
                            <input type="hidden" name="lsd[<?php echo esc_attr($obj->key()); ?>][single_share]" value="0">
                            <input type="checkbox" name="lsd[<?php echo esc_attr($obj->key()); ?>][single_share]" value="1" <?php echo ($obj->option('single_share') == 1 ? 'checked="checked"' : ''); ?>>
                            <span class="lsd-slider"></span>
                        </label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="lsd-spacer-10"></div>
        <div class="lsd-form-row">
			<div class="lsd-col-12">
				<?php LSD_Form::nonce('lsd_socials_form'); ?>
				<?php echo LSD_Form::submit(array(
					'label' => esc_html__('Save', 'listdom'),
					'id' => 'lsd_socials_save_button',
                    'class' => 'button button-hero button-primary',
				)); ?>
			</div>
        </div>
    </form>
</div>
<script>
jQuery('#lsd_socials_form').on('submit', function(event)
{
    event.preventDefault();

    // Add loading Class to the button
    jQuery("#lsd_socials_save_button").addClass('loading').html('<i class="lsd-icon fa fa-spinner fa-pulse fa-fw"></i>');

    var socials = jQuery("#lsd_socials_form").serialize();
    jQuery.ajax(
    {
        type: "POST",
        url: ajaxurl,
        data: "action=lsd_save_socials&" + socials,
        success: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_socials_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        },
        error: function()
        {
            // Remove loading Class from the button
            jQuery("#lsd_socials_save_button").removeClass('loading').html("<?php echo esc_js(esc_attr__('Save', 'listdom')); ?>");
        }
    });
});
</script>