<?php
// no direct access
defined('ABSPATH') or die();

// Filter Options
$options = get_post_meta($post->ID, 'lsd_filter', true);

$walker = new LSD_Walker_Taxonomy();
?>
<div class="lsd-metabox lsd-metabox-filter-options">
    <div class="lsd-form-row">
		<div class="lsd-col-12">
			<label class="lsd-filter-label"><?php esc_html_e('Categories', 'listdom'); ?></label>
            <p class="description"><?php esc_html_e("Leave the options if you don't want to filter listings by category. Then it will show all listings from all categories.", 'listdom'); ?></p>
			<ul class="lsd-categories">
				<?php
					wp_terms_checklist(0, array
					(
						'descendants_and_self'=>0,
						'taxonomy'=>LSD_Base::TAX_CATEGORY,
						'selected_cats'=>(isset($options[LSD_Base::TAX_CATEGORY]) ? $options[LSD_Base::TAX_CATEGORY] : array()),
						'popular_cats'=>false,
						'checked_ontop'=>false,
						'walker'=>$walker
					));
				?>
			</ul>
		</div>
    </div>
    <div class="lsd-form-row">
		<div class="lsd-col-12">
			<label class="lsd-filter-label"><?php esc_html_e('Locations', 'listdom'); ?></label>
            <p class="description"><?php esc_html_e("Leave all locations unchecked if you don't want to filter listings by their location.", 'listdom'); ?></p>
			<ul class="lsd-locations">
				<?php
					wp_terms_checklist(0, array
					(
						'descendants_and_self'=>0,
						'taxonomy'=>LSD_Base::TAX_LOCATION,
						'selected_cats'=>(isset($options[LSD_Base::TAX_LOCATION]) ? $options[LSD_Base::TAX_LOCATION] : array()),
						'popular_cats'=>false,
						'checked_ontop'=>false,
						'walker'=>$walker
					));
				?>
			</ul>
		</div>
    </div>
    <div class="lsd-form-row">
        <div class="lsd-col-12">
            <label class="lsd-filter-label" for="lsd_filter_options_tag"><?php esc_html_e('Tags', 'listdom'); ?></label>
            <input id="lsd_filter_options_tag" type="text" name="lsd[filter][<?php echo LSD_Base::TAX_TAG; ?>]" value="<?php echo (isset($options[LSD_Base::TAX_TAG]) ? esc_attr($options[LSD_Base::TAX_TAG]) : ''); ?>" class="widefat" />
			<p class="description"><?php esc_html_e('Insert your desired tags separated by comma.', 'listdom'); ?></p>
        </div>
    </div>
    <div class="lsd-form-row">
		<div class="lsd-col-12">
			<label class="lsd-filter-label"><?php esc_html_e('Features', 'listdom'); ?></label>
            <p class="description"><?php esc_html_e("Don't select any option if you don't want to filter by features.", 'listdom'); ?></p>
			<ul class="lsd-features">
				<?php
					wp_terms_checklist(0, array
					(
						'descendants_and_self'=>0,
						'taxonomy'=>LSD_Base::TAX_FEATURE,
						'selected_cats'=>(isset($options[LSD_Base::TAX_FEATURE]) ? $options[LSD_Base::TAX_FEATURE] : array()),
						'popular_cats'=>false,
						'checked_ontop'=>false,
						'walker'=>$walker
					));
				?>
			</ul>
		</div>
    </div>
    <div class="lsd-form-row">
		<div class="lsd-col-12">
			<label class="lsd-filter-label"><?php esc_html_e('Labels', 'listdom'); ?></label>
            <p class="description"><?php esc_html_e("Don't select any option if you don't want to filter by labels.", 'listdom'); ?></p>
			<ul class="lsd-features">
				<?php
				wp_terms_checklist(0, array
				(
					'descendants_and_self'=>0,
					'taxonomy'=>LSD_Base::TAX_LABEL,
					'selected_cats'=>(isset($options[LSD_Base::TAX_LABEL]) ? $options[LSD_Base::TAX_LABEL] : array()),
					'popular_cats'=>false,
					'checked_ontop'=>false,
					'walker'=>$walker
				));
				?>
			</ul>
		</div>
    </div>
    <div class="lsd-form-row">
		<div class="lsd-col-12">
			<label class="lsd-filter-label"><?php esc_html_e('Authors', 'listdom'); ?></label>
            <p class="description"><?php esc_html_e("Don't select any option if you don't want to filter by authors.", 'listdom'); ?></p>
			<ul class="lsd-authors">
				<?php
					$authors = get_users(array
					(
						'role__not_in'=>array('subscriber','contributor'),
						'orderby'=>'post_count',
						'order'=>'DESC',
						'number'=>'-1',
						'fields'=>array('ID', 'display_name')
					));

					$selected_authors = (isset($options['authors']) ? $options['authors'] : array());
					foreach($authors as $author)
					{
						echo '<li><label><input id="in_lsd_author_'.esc_attr($author->ID).'" name="lsd[filter][authors][]" type="checkbox" value="'.esc_attr($author->ID).'" '.(in_array($author->ID, $selected_authors) ? 'checked="checked"' : '').' /> '.esc_html($author->display_name).'</label></li>';
					}
				?>
			</ul>
		</div>
    </div>
</div>