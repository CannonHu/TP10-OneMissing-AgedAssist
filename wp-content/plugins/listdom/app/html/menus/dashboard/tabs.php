<?php
// no direct access
defined('ABSPATH') or die();
?>
<h2 class="nav-tab-wrapper">
    <a class="nav-tab <?php echo ($this->tab == 'dashboard' ? 'nav-tab-active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=listdom')); ?>"><?php esc_html_e('Dashboard', 'listdom'); ?></a>
    <a class="nav-tab <?php echo ($this->tab == 'changelog' ? 'nav-tab-active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=listdom&tab=changelog')); ?>"><?php esc_html_e('Change Log', 'listdom'); ?></a>
    <a class="nav-tab <?php echo ($this->tab == 'credits' ? 'nav-tab-active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=listdom&tab=credits')); ?>"><?php esc_html_e('Credits', 'listdom'); ?></a>
    
    <?php
        /**
         * For showing new tabs in admin dashboard by third party plugins
         */
        do_action('lsd_admin_dashboard_tabs', $this->tab);
    ?>
</h2>