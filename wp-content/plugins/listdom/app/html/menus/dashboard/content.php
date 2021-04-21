<?php
// no direct access
defined('ABSPATH') or die();

switch($this->tab)
{
    case 'changelog':
        
        $this->include_html_file('menus/dashboard/tabs/changelog.php');
        break;
    
    case 'credits':
        
        $this->include_html_file('menus/dashboard/tabs/credits.php');
        break;

    case 'dashboard':
        
        $this->include_html_file('menus/dashboard/tabs/dashboard.php');
        break;

    default:
        /**
         * For showing new tabs in admin dashboard by third party plugins
         */
        do_action('lsd_admin_dashboard_contents', $this->tab);
        break;
}