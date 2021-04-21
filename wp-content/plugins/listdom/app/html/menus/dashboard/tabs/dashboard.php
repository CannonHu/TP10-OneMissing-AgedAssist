<?php
// no direct access
defined('ABSPATH') or die();
?>
<div class="lsd-dashboard-wrap">
    <div class="welcome-panel">
		<div class="welcome-panel-content">
            <h2><?php esc_html_e('Welcome to Listdom!', 'listdom'); ?></h2>
            <p class="about-description"><?php esc_html_e('We’ve assembled some links to get you started:', 'listdom'); ?></p>
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column">
                    <h3><?php esc_html_e('Get Started', 'listdom'); ?></h3>
                    <a class="button button-primary button-hero" href="https://totalery.com/listdom/documentation/"><?php esc_html_e('Check Documentation', 'listdom'); ?></a>
                    <p><?php esc_html_e('or,', 'listdom'); ?> <a href="https://totalery.com/support/"><?php esc_html_e('contact our support team!', 'listdom'); ?></a></p>
                </div>
                <div class="welcome-panel-column">
                    <h3><?php esc_html_e('Next Steps', 'listdom'); ?></h3>
                    <ul>
                        <li><a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy='.LSD_Base::TAX_CATEGORY.'&post_type='.LSD_Base::PTYPE_LISTING)); ?>" class="welcome-icon dashicons-category"><?php esc_html_e('Manage Categories', 'listdom'); ?></a></li>
                        <li><a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy='.LSD_Base::TAX_LOCATION.'&post_type='.LSD_Base::PTYPE_LISTING)); ?>" class="welcome-icon dashicons-location"><?php esc_html_e('Manage Locations', 'listdom'); ?></a></li>
                        <?php if($this->isPro()): ?><li><a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy='.LSD_Base::TAX_ATTRIBUTE.'&post_type='.LSD_Base::PTYPE_LISTING)); ?>" class="welcome-icon dashicons-nametag"><?php esc_html_e('Manage Attributes', 'listdom'); ?></a></li><?php endif; ?>
                    </ul>
                </div>
                <div class="welcome-panel-column">
                    <h3><?php esc_html_e('Listings', 'listdom'); ?></h3>
                    <ul>
                        <li><a href="<?php echo esc_url(admin_url('edit.php?post_type='.LSD_Base::PTYPE_LISTING)); ?>" class="welcome-icon dashicons-media-text"><?php esc_html_e('Manage/Add Listings', 'listdom'); ?></a></li>
                        <li><a href="<?php echo esc_url(admin_url('edit.php?post_type='.LSD_Base::PTYPE_SHORTCODE)); ?>" class="welcome-icon dashicons-grid-view"><?php esc_html_e('Manage/Add Shortcodes', 'listdom'); ?></a></li>
                        <li><a href="<?php echo esc_url(admin_url('admin.php?page=listdom-settings')); ?>" class="welcome-icon dashicons-admin-settings"><?php esc_html_e('Configure the Listdom', 'listdom'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>