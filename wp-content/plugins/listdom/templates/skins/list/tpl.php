<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Skins_List $this */

// Get HTML of Listings
$listings_html = $this->listings_html();

// Add List Skin JS codes to footer
$assets = new LSD_Assets();
$assets->footer('<script>
jQuery(document).ready(function()
{
    jQuery("#lsd_skin'.$this->id.'").listdomListSkin(
    {
        id: "'.$this->id.'",
        load_more: '.($this->load_more ? 'true' : 'false').',
        ajax_url: "'.admin_url('admin-ajax.php', NULL).'",
        atts: "'.http_build_query(array('atts'=>$this->atts), '', '&').'",
        next_page: "'.$this->next_page.'",
        limit: "'.$this->limit.'"
    });
});
</script>');
?>
<div class="lsd-list-view-wrapper <?php echo esc_attr($this->html_class); ?> lsd-style-<?php echo esc_attr($this->style); ?> lsd-font-m" id="lsd_skin<?php echo esc_attr($this->id); ?>" data-next-page="<?php echo esc_attr($this->next_page); ?>">

    <?php if($this->sm_shortcode and $this->sm_position == 'top') echo LSD_Kses::form($this->get_search_module()); ?>

    <?php
    /**
     * Top Position of List View
     */
    if($this->map_provider and isset($this->skin_options['map_position']) and $this->skin_options['map_position'] == 'top')
    {
        echo '<div class="lsd-list-view-top-wrapper">';
        echo lsd_map($this->search(array('posts_per_page'=>$this->skin_options['maplimit'])), array
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
        echo '</div>';
    }
    ?>

    <?php if($this->sm_shortcode and $this->sm_position == 'before_listview') echo LSD_Kses::form($this->get_search_module()); ?>

    <?php echo LSD_Kses::form($this->get_sortbar()); ?>

    <div class="lsd-list-view-listings-wrapper lsd-viewstyle-list">
        <div class="lsd-listing-wrapper">
            <?php echo LSD_Kses::page($listings_html); ?>
        </div>
    </div>

    <?php echo LSD_Kses::element($this->get_loadmore_button()); ?>

    <?php
    /**
     * Bottom Position of List View
     */
    if($this->map_provider and isset($this->skin_options['map_position']) and $this->skin_options['map_position'] == 'bottom')
    {
        echo '<div class="lsd-list-view-bottom-wrapper">';
        echo lsd_map($this->search(array('posts_per_page'=>$this->skin_options['maplimit'])), array
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
        echo '</div>';
    }
    ?>
</div>