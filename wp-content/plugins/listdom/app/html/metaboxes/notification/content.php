<?php
// no direct access
defined('ABSPATH') or die();

$hook = get_post_meta($post->ID, 'lsd_hook', true);
$content = get_post_meta($post->ID, 'lsd_content', true);
?>
<div class="lsd-metabox lsd-notification-content-metabox">
    <div class="lsd-form-row lsd-mt-4 lsd-mb-4">
        <div class="lsd-col-1">
            <?php echo LSD_Form::label(array(
                'for' => 'lsd_notification_content_hook',
                'title' => esc_html__('Hook', 'listdom'),
                'class' => 'lsd-label',
            )); ?>
        </div>
        <div class="lsd-col-5">
            <?php echo LSD_Form::select(array(
                'id' => 'lsd_notification_content_hook',
                'name' => 'lsd[hook]',
                'value' => $hook,
                'options' => LSD_Notifications::get_notification_hooks(),
            )); ?>
        </div>
    </div>
    <div class="lsd-form-row">
        <div class="lsd-col-12">
            <?php wp_editor($content, 'lsd_notification_content_content', array(
                'textarea_name' => 'lsd[content]'
            )); ?>
        </div>
    </div>
    <div class="lsd-notification-guide lsd-mt-4 lsd-mb-4">
        <div class="lsd-alert lsd-info">
            <ul>
                <li><?php esc_html_e("Notification title will be used as email subject.", 'listdom'); ?></li>
                <li><?php esc_html_e("Please assign your notification to the correct hook otherwise it won't work as expected!", 'listdom'); ?></li>
                <li><?php echo sprintf(esc_html__('You can use %s of right side to insert dynamic data into the notification content and subject.', 'listdom'), '<strong>'.esc_html__("#placeholders#", 'listdom').'</strong>'); ?></li>
                <li><?php echo esc_html__('You can add custom recipients to the email using CC and BCC options.', 'listdom'); ?></li>
                <li><?php echo esc_html__('Shortcodes and HTML codes are allowed in content.', 'listdom'); ?></li>
                <li><?php echo esc_html__('If you want to disable a notification, you can simply unpublish it.', 'listdom'); ?></li>
            </ul>
        </div>
    </div>
</div>