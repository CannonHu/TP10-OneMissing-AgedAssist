<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Skins_Masonry $this */

// Get HTML of Listings
$listings_html = $this->listings_html();

// Add masonry assets to the page
$assets = new LSD_Assets();
$assets->isotope();

// Add Masonry Skin JS codes to footer
$assets->footer('<script>
jQuery(document).ready(function()
{
    jQuery("#lsd_skin'.$this->id.'").listdomMasonrySkin(
    {
        id: "'.$this->id.'",
        ajax_url: "'.admin_url('admin-ajax.php', NULL).'",
        atts: "'.http_build_query(array('atts'=>$this->atts), '', '&').'",
        rtl: '.(is_rtl() ? 'true' : 'false').',
    });
});
</script>');
?>
<div class="lsd-masonry-view-wrapper <?php echo esc_attr($this->html_class); ?> lsd-style-<?php echo esc_attr($this->style); ?> lsd-font-m" id="lsd_skin<?php echo esc_attr($this->id); ?>">

    <?php if($this->sm_shortcode and $this->sm_position == 'top') echo LSD_Kses::form($this->get_search_module()); ?>

    <?php if(trim($this->filter_by)) echo LSD_Kses::element($this->filters()); ?>

    <?php if($this->sm_shortcode and $this->sm_position == 'before_listview') echo LSD_Kses::form($this->get_search_module()); ?>

    <div class="lsd-masonry-view-listings-wrapper">
        <div class="lsd-listing-wrapper lsd-row">
            <?php echo LSD_Kses::page($listings_html); ?>
        </div>
    </div>

</div>