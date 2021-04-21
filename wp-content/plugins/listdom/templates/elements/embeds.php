<?php
// no direct access
defined('ABSPATH') or die();

$embeds = get_post_meta($post_id, 'lsd_embeds', true);
if(!is_array($embeds)) $embeds = array();

// There is no Embed Codes!
if(!count($embeds)) return '';
?>
<div class="lsd-embed-codes">
    <ul>
        <?php foreach($embeds as $embed): if(!isset($embed['code']) or (isset($embed['code']) and !trim($embed['code']))) continue; ?>
        <li <?php echo lsd_schema()->subjectOf(); ?> <?php echo lsd_schema()->scope()->type('http://schema.org/VideoObject', NULL); ?>>
			<?php if(isset($embed['name']) and trim($embed['name'])): ?>
            <h2 class="lsd-single-page-section-title" <?php echo lsd_schema()->name(); ?> ><?php echo esc_html($embed['name']); ?></h2>
            <?php endif; ?>
            <div class="lsd-embed-code-wrapper">
                <?php echo LSD_Kses::embed($embed['code']); ?>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>