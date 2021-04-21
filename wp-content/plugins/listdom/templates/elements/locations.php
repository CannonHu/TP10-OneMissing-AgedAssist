<?php
// no direct access
defined('ABSPATH') or die();

$locations = wp_get_post_terms($post_id, LSD_Base::TAX_LOCATION);
if(!is_array($locations) or (is_array($locations) and !count($locations))) return '';
?>
<ul class="lsd-locations-list">
    <?php foreach($locations as $location): ?>
    <li class="lsd-locations-list-item" <?php echo lsd_schema()->scope()->type('http://schema.org/LocationFeatureSpecification', NULL); ?> >
		<a href="<?php echo esc_url(get_term_link($location->term_id, LSD_Base::TAX_LOCATION)); ?>" <?php echo lsd_schema()->name(); ?> >
			<i class="lsd-icon fas fa-map-marker-alt"></i>
			<?php echo esc_html($location->name); ?>
		</a>
	</li>
    <?php endforeach; ?>
</ul>