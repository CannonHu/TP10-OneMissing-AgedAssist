<?php
// no direct access
defined('ABSPATH') or die();
?>
<div class="wrap about-wrap lsd-wrap">
    <h1><?php esc_html_e('Settings', 'listdom'); ?></h1>
    <div class="about-text">
		<?php echo sprintf(esc_html__("Easily configure %s to change its functionality and look.", 'listdom'), '<strong>Listdom</strong>'); ?>
    </div>
    
    <!-- Settings Tabs -->
    <?php $this->include_html_file('menus/settings/tabs.php'); ?>
    
    <!-- Settings Content -->
    <?php $this->include_html_file('menus/settings/content.php'); ?>
    
</div>