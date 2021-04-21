<?php
// no direct access
defined('ABSPATH') or die();
?>
<div class="wrap about-wrap lsd-wrap">
    <h1><?php echo sprintf(($this->isLite() ? esc_html__('Listdom %s', 'listdom') : esc_html__('Listdom Pro %s', 'listdom')), '<span>v'.LSD_VERSION.'</span>'); ?></h1>

    <?php if($this->isLite() and $this->isPastFromInstallationTime(604800)): // 7 days ?>
    <p>
        <?php echo LSD_Base::alert($this->upgradeMessage(), 'error'); ?>
    </p>
    <?php endif; ?>

    <div class="about-text">
		<?php echo sprintf(esc_html__("Thanks for using %s. It's a great plugin for creating many listings in different categories and show them on nice map and other skins such as List and Grid skins.", 'listdom'), '<strong>Listdom</strong>'); ?>
    </div>
    
    <!-- Dashboard Tabs -->
    <?php $this->include_html_file('menus/dashboard/tabs.php'); ?>
    
    <!-- Dashboard Content -->
    <?php $this->include_html_file('menus/dashboard/content.php'); ?>
    
</div>