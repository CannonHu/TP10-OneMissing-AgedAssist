<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Skins_Slider $this */

// Get HTML of Listings
$listings_html = $this->listings_html();

// Add List Skin JS codes to footer
$assets = new LSD_Assets();
$assets->footer('<script>
jQuery(document).ready(function()
{
    jQuery("#lsd_skin'.$this->id.'").listdomSliderSkin(
    {
        id: "'.$this->id.'",
        ajax_url: "'.admin_url('admin-ajax.php', NULL).'",
        atts: "'.http_build_query(array('atts'=>$this->atts), '', '&').'",
        items: 1,
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        dots: false,
        nav: true,
    });
});
</script>');
?>
<div class="lsd-slider-view-wrapper <?php echo esc_attr($this->html_class); ?> lsd-style-<?php echo esc_attr($this->style); ?> lsd-font-m" id="lsd_skin<?php echo esc_attr($this->id); ?>">

    <div class="lsd-slider-view-listings-wrapper">
        <div class="lsd-listing-wrapper">
            <?php echo LSD_Kses::page($listings_html); ?>
        </div>
    </div>

</div>