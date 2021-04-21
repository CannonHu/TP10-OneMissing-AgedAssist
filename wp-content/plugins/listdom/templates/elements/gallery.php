<?php
// no direct access
defined('ABSPATH') or die();

$width = isset($params['width']) ? $params['width'] : 200;
$height = isset($params['height']) ? $params['height'] : 200;
$lightbox = (!isset($params['lightbox']) or (isset($params['lightbox']) and $params['lightbox'])) ? true : false;

$gallery = get_post_meta($post_id, 'lsd_gallery', true);
if(!is_array($gallery)) $gallery = array();

// There is no Gallery!
if(!count($gallery)) return '';

$imageItemProp = 'itemprop="http://schema.org/image"';
?>
<div class="lsd-image-gallery <?php echo ($lightbox ? 'lsd-image-lightbox' : ''); ?>" <?php echo lsd_schema()->scope()->type('http://schema.org/ImageGallery', NULL); ?>>
    <?php
        foreach($gallery as $id)
        {
            $thumb = wp_get_attachment_image_src($id, array($width, $height));
            $full = wp_get_attachment_image_src($id, 'full');

            if(!$thumb or !$full) continue;

            echo '<a href="'.esc_url($full[0]).'" '.lsd_schema()->associatedMedia().'>
                <img src="'.esc_url($thumb[0]).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" '.$imageItemProp.'>
            </a>';
        }
    ?>
</div>