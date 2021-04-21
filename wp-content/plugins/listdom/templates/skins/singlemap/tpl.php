<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Skins_Singlemap $this */
?>
<div class="lsd-singlemap-view-wrapper <?php echo esc_attr($this->html_class); ?>">

    <?php if($this->sm_shortcode) echo LSD_Kses::form($this->get_search_module()); ?>

    <?php echo lsd_map($this->listings, array
    (
        'provider'=>$this->map_provider,
        'clustering'=>(isset($this->skin_options['clustering']) ? $this->skin_options['clustering'] : true),
        'clustering_images'=>(isset($this->skin_options['clustering_images']) ? $this->skin_options['clustering_images'] : ''),
        'mapstyle'=>(isset($this->skin_options['mapstyle']) ? $this->skin_options['mapstyle'] : ''),
        'id'=>$this->id,
        'onclick'=>(isset($this->skin_options['mapobject_onclick']) ? $this->skin_options['mapobject_onclick'] : 'infowindow'),
        'mapcontrols'=>$this->mapcontrols,
        'atts'=>$this->atts,
        'mapsearch'=>$this->mapsearch,
        'autoGPS'=>$this->autoGPS,
        'max_bounds'=>$this->maxBounds,
    ));
    ?>
</div>