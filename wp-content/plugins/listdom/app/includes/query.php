<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Query')):

/**
 * Listdom Query Class.
 *
 * @class LSD_Query
 * @version	1.0.0
 */
class LSD_Query extends LSD_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function attribute($key, $value)
    {
        list($id, $type) = explode('-', $key);

        if($id == 'address') $field = 'lsd_address';
        elseif($id == 'price') $field = 'lsd_price';
        elseif($id == 'class') $field = 'lsd_price_class';
        else $field = 'lsd_attribute_'.$id;

        $query = array();
        switch($type)
        {
            case 'eq':

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => '='
                );

                break;

            case 'neq':

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => '!='
                );

                break;

            case 'gr':

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => '>',
                    'type' => 'NUMERIC'
                );

                break;

            case 'grq':

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                );

                break;

            case 'lw':

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => '<',
                    'type' => 'NUMERIC'
                );

                break;

            case 'lwq':

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => '<=',
                    'type' => 'NUMERIC'
                );

                break;

            case 'lk':

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => 'LIKE'
                );

                break;

            case 'nlk':

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => 'NOT LIKE'
                );

                break;

            case 'in':

                // Force to Array
                if(!is_array($value)) $value = array($value);

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => 'IN'
                );

                break;

            case 'nin':

                // Force to Array
                if(!is_array($value)) $value = array($value);

                $query = array(
                    'key'     => $field,
                    'value'   => $value,
                    'compare' => 'NOT IN'
                );

                break;

            case 'bt':

                $query = array(
                    'key'     => $field,
                    'value'   => explode(':', $value),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN'
                );

                break;

            case 'nbt':

                $query = array(
                    'key'     => $field,
                    'value'   => explode(':', $value),
                    'compare' => 'NOT BETWEEN'
                );

                break;

            case 'ex':

                $query = array(
                    'key'     => $field,
                    'compare' => 'EXISTS'
                );

                break;

            case 'nex':

                $query = array(
                    'key'     => $field,
                    'compare' => 'NOT EXISTS'
                );

                break;
        }

        return count($query) ? $query : false;
    }
}

endif;