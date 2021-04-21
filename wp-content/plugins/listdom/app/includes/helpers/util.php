<?php
// no direct access
defined('ABSPATH') or die();

function lsd_map($listings = array(), $args = array())
{
    // Map Provider
    $provider = LSD_Map_Provider::get((isset($args['provider']) ? $args['provider'] : NULL));

    if($provider === LSD_MP_GOOGLE) return lsd_googlemap($listings, $args);
    elseif($provider === LSD_MP_LEAFLET) return lsd_leaflet($listings, $args);

    return NULL;
}

function lsd_googlemap($listings = array(), $args = array())
{
    ob_start();
    include lsd_template('maps/google.php');
    return ob_get_clean();
}

function lsd_leaflet($listings = array(), $args = array())
{
    ob_start();
    include lsd_template('maps/leaflet.php');
    return ob_get_clean();
}

function lsd_schema()
{
    return new LSD_Schema();
}