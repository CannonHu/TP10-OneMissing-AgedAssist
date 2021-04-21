<?php
// no direct access
defined('ABSPATH') or die();

$post_attributes = get_post_meta($post_id, 'lsd_attributes', true);

if(!is_array($post_attributes)) $post_attributes = array();
if(!count($post_attributes)) return '';

$taxonomy = new LSD_Taxonomies_Attribute();
$terms = $taxonomy->get_terms();

// Listing Category
$entity = new LSD_Entity_Listing($post_id);
$category = $entity->get_data_category();

$attributes = array();
foreach($terms as $term)
{
    // Get all category status
    $all_categories = get_term_meta($term->term_id, 'lsd_all_categories', true);
    if(trim($all_categories) == '') $all_categories = 1;

    // Get specific categories
    $categories = get_term_meta($term->term_id, 'lsd_categories', true);
    if($all_categories) $categories = array();

    // This attribute is not specified for listing category
    if(!$all_categories and (count($categories) and !isset($categories[$category->term_id]))) continue;
    $attributes[$term->term_id] = $term;
}
?>
<?php $i = 0; foreach($attributes as $key=>$attribute): $att = new LSD_Entity_Attribute($attribute->term_id); ?>
    <?php if($att->type == 'separator'): ?>
        <?php
            if($i != 0)
            {
                echo '</div>';
                $i = 0;
            }
        ?>
        <div class="lsd-row">
            <div class="lsd-col-12">
                <div class="lsd-separator"><?php echo esc_html($attribute->name); ?></div>
            </div>
        </div>
    <?php else: ?>
        <?php if($i == 0): ?><div class="lsd-row"><?php endif; ?>
        <div class="lsd-col-6" <?php echo LSD_Entity_Attribute::schema($attribute->term_id); ?>>
            <span class="lsd-attr-key"><?php if(isset($show_icons) and $show_icons): ?><span class="lsd-attr-icon"><?php echo LSD_Kses::element($att->icon()); ?></span><?php endif; ?><?php echo esc_html($attribute->name); ?>: </span>
            <span class="lsd-attr-value"><?php echo LSD_Kses::element($att->render((isset($post_attributes[$key]) ? $post_attributes[$key] : ''))); ?></span>
        </div>
        <?php if($i == 1): ?></div><?php endif; ?>
    <?php $i++; if($i == 2) $i = 0; endif; ?>
<?php endforeach; ?>
<?php if($i != 0) echo '</div>';