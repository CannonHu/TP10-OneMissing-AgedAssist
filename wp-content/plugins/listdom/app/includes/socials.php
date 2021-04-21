<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Socials')):

/**
 * Listdom Socials Class.
 *
 * @class LSD_Socials
 * @version	1.0.0
 */
class LSD_Socials extends LSD_Base
{
    public $path;
    public $key;
    public $label;
    public $option;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        $this->path = $this->get_listdom_path().'/app/includes/socials/';
	}
    
    public function init()
    {
        add_action('lsd_social_networks_profile_form', array($this, 'profileForm'));
        add_action('lsd_social_networks_profile_save', array($this, 'profileSave'));
    }

    /**
     * @param string $network
     * @param array $options
     * @return bool|object
     */
    public function get($network, $options = NULL)
    {
        $class = 'LSD_Socials_'.ucfirst($network);

        // Class doesn't exists
        if(!class_exists($class)) return false;

        // Return the object
        $obj = new $class();
        $obj->option = $options;

        return $obj;
    }

    public function key()
    {
        return $this->key;
    }

    public function label()
    {
        return $this->label;
    }

    public function option($name)
    {
        return isset($this->option[$name]) ? $this->option[$name] : NULL;
    }

    public function profileForm($user)
    {
        $networks = LSD_Options::socials();
        foreach($networks as $network=>$values)
        {
            $obj = $this->get($network, $values);

            // Social Network is not Enabled
            if(!$obj or ($obj and !$obj->option('profile'))) continue;

            echo '<tr>';
            echo '<th><label for="lsd_'.$obj->key().'">'.$obj->label().'</label></th>';
            echo '<td><input type="text" name="lsd_'.$obj->key().'" id="lsd_'.$obj->key().'" value="'.esc_attr(get_the_author_meta('lsd_'.$obj->key(), $user->ID)).'" class="regular-text ltr"></td>';
            echo '</tr>';
        }
    }

    public function profileSave($user_id)
    {
        $networks = LSD_Options::socials();
        foreach($networks as $network=>$values)
        {
            $obj = $this->get($network, $values);

            // Social Network is not Enabled
            if(!$obj or ($obj and !$obj->option('profile'))) continue;

            // Save
            update_user_meta($user_id, 'lsd_'.$obj->key(), sanitize_text_field($_POST['lsd_'.$obj->key()]));
        }
    }

    public function url($post_id)
    {
    }

    public function owner($link)
    {
    }
}

endif;