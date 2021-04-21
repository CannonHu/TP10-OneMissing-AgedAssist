<?php
/**
 * Plugin Name: Listdom
 * Plugin URI: https://totalery.com/listdom/
 * Description: An advanced but simple tool to list everything on your website and show them in modern responsive skins like list, grid, map and masonry.
 * Version: 2.1.0
 * Author: Totalery
 * Author URI: https://totalery.com/
 * Requires at least: 4.0.0
 * Tested up to: 5.7
 *
 * Text Domain: listdom
 * Domain Path: /i18n/languages/
 */

// Initialize the Listdom or not?!
$init = true;

// Check Minimum PHP version
if(version_compare(phpversion(), '5.6', '<'))
{
    $init = false;

    add_action('user_admin_notices', 'lsd_admin_notice_php_min_version');
    function lsd_admin_notice_php_min_version()
    {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php echo sprintf(esc_html__("%s needs at-least PHP 5.3.10 or higher while your server PHP version is %s. Please contact your host provider and ask them to upgrade PHP of your server or change your host provider completely.", 'listdom'), '<strong>Listdom</strong>', '<strong>'.phpversion().'</strong>'); ?></p>
        </div>
        <?php
    }
}

// Check Minimum WP version
global $wp_version;
if(version_compare($wp_version, '4.0.0', '<'))
{
    $init = false;

    add_action('user_admin_notices', 'lsd_admin_notice_wp_min_version');
    function lsd_admin_notice_wp_min_version()
    {
        global $wp_version;
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php echo sprintf(esc_html__("%s needs at-least WordPress 4.0.0 or higher while your WordPress version is %s. Please update your WordPress to latest version first.", 'listdom'), '<strong>Listdom</strong>', '<strong>'.esc_html($wp_version).'</strong>'); ?></p>
        </div>
        <?php
    }
}

// Plugin initialized before! Maybe by Pro or Lite version
if(function_exists('listdom')) $init = false;

// Run the Listdom
if($init) require_once 'LSD.php';