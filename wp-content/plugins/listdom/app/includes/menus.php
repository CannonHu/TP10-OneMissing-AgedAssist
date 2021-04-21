<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Menus')):

/**
 * Listdom Menus Class.
 *
 * @class LSD_Menus
 * @version	1.0.0
 */
class LSD_Menus extends LSD_Base
{
    protected $dashboard;
    protected $settings;
    protected $ix;
    public $tab;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}
    
    public function init()
    {
        // Initialize menus
        $this->dashboard = new LSD_Menus_Dashboard();
        $this->settings = new LSD_Menus_Settings();
        $this->ix = new LSD_Menus_IX();

        // Register Listdom Menus
        add_action('admin_menu', array($this, 'register_menus'), 1);
        add_action('parent_file', array($this, 'mainmenu_selection'));
        add_action('submenu_file', array($this, 'submenu_selection'));

        // Add Separators
        add_action('admin_init', array($this, 'add_separators'));
    }
    
    public function register_menus()
    {
        add_menu_page(esc_html__('Listdom', 'listdom'), esc_html__('Listdom', 'listdom'), 'manage_options', 'listdom', array($this->dashboard, 'output'), 'dashicons-location', 26);
        add_submenu_page('listdom', esc_html__('Shortcodes', 'listdom'), esc_html__('Shortcodes', 'listdom'), 'manage_options', 'edit.php?post_type='.LSD_Base::PTYPE_SHORTCODE, NULL, 2);
        add_submenu_page('listdom', esc_html__('Search Builder', 'listdom'), esc_html__('Search Builder', 'listdom'), 'manage_options', 'edit.php?post_type='.LSD_Base::PTYPE_SEARCH, NULL, 3);
        add_submenu_page('listdom', esc_html__('Notifications', 'listdom'), esc_html__('Notifications', 'listdom'), 'manage_options', 'edit.php?post_type='.LSD_Base::PTYPE_NOTIFICATION, NULL, 4);
        add_submenu_page('listdom', esc_html__('Settings', 'listdom'), esc_html__('Settings', 'listdom'), 'manage_options', 'listdom-settings', array($this->settings, 'output'), 5);
        add_submenu_page('listdom', esc_html__('Import / Export', 'listdom'), esc_html__('Import / Export', 'listdom'), 'manage_options', 'listdom-ix', array($this->ix, 'output'), 6);

        add_submenu_page('listdom', esc_html__('Documentation', 'listdom'), esc_html__('Documentation', 'listdom'), 'manage_options', 'https://totalery.com/listdom/documentation/', NULL, 30);
        add_submenu_page('listdom', esc_html__('Support', 'listdom'), esc_html__('Support', 'listdom'), 'manage_options', 'https://totalery.com/support/', NULL, 31);
    }

    public function mainmenu_selection($parent_file)
    {
        global $current_screen;
        $post_type = $current_screen->post_type;

        // Don't do anything if the post type is not Listdom Post Type
        if(!in_array($post_type, array(LSD_Base::PTYPE_SHORTCODE, LSD_Base::PTYPE_SEARCH, LSD_Base::PTYPE_NOTIFICATION))) return $parent_file;

        return 'listdom';
    }

    public function submenu_selection($submenu_file)
    {
        global $current_screen;
        $post_type = $current_screen->post_type;

        // Don't do anything if the post type is not Listdom Post Type
        if(!in_array($post_type, array(LSD_Base::PTYPE_SHORTCODE, LSD_Base::PTYPE_SEARCH, LSD_Base::PTYPE_NOTIFICATION))) return $submenu_file;

        return 'edit.php?post_type='.$post_type;
    }

    public function add_separators()
    {
        if(!is_admin()) return false;

        global $menu;
        if(!is_array($menu)) return false;

        $sep = NULL;
        $do_start = NULL;
        $start = NULL;
        $end = NULL;
        $do_end = NULL;

        $i = 0;
        $previous = NULL;
        foreach($menu as $m)
        {
            // Next menu of end is separator so we don't need to add separator again
            if($end and is_null($do_end) and isset($m['4']) and strpos($m['4'], 'menu-separator') !== false) $do_end = false;
            elseif($end and is_null($do_end)) $do_end = true;

            if(!$sep and isset($m['4']) and strpos($m['4'], 'menu-separator') !== false) $sep = $m;
            if(!$start and isset($m['5']) and strpos($m['5'], 'page_listdom') !== false) $start = ((int) $i);
            if(!$end and isset($m['5']) and strpos($m['5'], 'listdom-listing') !== false) $end = ((int) $i)+2;

            // Previous menu of start is separator so we don't need to add separator again
            if($start and is_null($do_start)  and is_null($do_start) and isset($previous['4']) and strpos($previous['4'], 'menu-separator') !== false) $do_start = false;
            elseif($start and is_null($do_start)) $do_start = true;

            $i++;
            $previous = $m;

            if($sep and $start and $end and !is_null($do_end)) break;
        }

        if(is_null($do_start)) $do_start = true;
        if(is_null($do_end)) $do_end = true;

        // Start not found! Maybe because current user is not administrator
        if(!$start) return false;

        // Separator not found!
        if(!$sep) return false;

        // Add First Separator
        if($do_start) $menu = array_merge(
            array_slice($menu, 0, $start),
            array($sep),
            array_slice($menu, $start)
        );

        // Add Second Separator
        if($do_end) $menu = array_merge(
            array_slice($menu, 0, $end),
            array($sep),
            array_slice($menu, $end)
        );

        if(isset($menu[ $start - 1 ])) $menu[ $start - 1 ][4] .= ' menu-top-last';
        if(isset($menu[ $start + 1 ])) $menu[ $start + 1 ][4] .= ' menu-top-first';

        if(isset($menu[ $end - 1 ])) $menu[ $end - 1 ][4] .= ' menu-top-last';
        if(isset($menu[ $end + 1 ])) $menu[ $end + 1 ][4] .= ' menu-top-first';

        return true;
    }
}

endif;