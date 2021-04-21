<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_Skins $shortcode */
$shortcode = LSD_Payload::get('shortcode');

// Listing Link Method
$listing_link_method = ($shortcode ? $shortcode->get_listing_link_method() : 'normal');

// Listing Image
$image = get_the_post_thumbnail($post_id, $size, (string) lsd_schema()->prop('contentUrl'));
?>
<?php if(in_array($listing_link_method, array('normal', 'blank'))): ?>
<a class="lsd-cover-img-wrapper <?php echo (trim($image) ? 'lsd-has-image' : ''); ?>" href="<?php echo esc_url(get_the_permalink($post_id)); ?>" <?php echo ($listing_link_method === 'blank' ? 'target="_blank"' : ''); ?> <?php echo lsd_schema()->url()->scope()->type('http://schema.org/ImageObject'); ?>>
    <?php echo (trim($image) ? LSD_Kses::element($image) : '<div class="lsd-no-image"><i class="lsd-icon fa fa-camera fa-5x"></i></div>'); ?>
</a>
<?php else: echo (trim($image) ? LSD_Kses::element($image) : '<div class="lsd-no-image"><i class="lsd-icon fa fa-camera fa-5x"></i></div>'); ?>
<?php endif;