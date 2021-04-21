<?php
// no direct access
defined('ABSPATH') or die();

/** @var LSD_PTypes_Listing_Single $this */

// Element Options
$elements = isset($this->details_page_options['elements']) ? $this->details_page_options['elements'] : array();

$title = (isset($elements['title']) and isset($elements['title']['enabled']) and $elements['title']['enabled']) ? $this->title() : '';
$price = (isset($elements['price']) and isset($elements['price']['enabled']) and $elements['price']['enabled']) ? $this->price() : '';
$address = (isset($elements['address']) and isset($elements['address']['enabled']) and $elements['address']['enabled']) ? $this->address() : '';
$locations = (isset($elements['locations']) and isset($elements['locations']['enabled']) and $elements['locations']['enabled']) ? $this->locations() : '';
$share = (isset($elements['share']) and isset($elements['share']['enabled']) and $elements['share']['enabled']) ? $this->share() : '';
$categories = (isset($elements['categories']) and isset($elements['categories']['enabled']) and $elements['categories']['enabled']) ? $this->categories() : '';
$image = (isset($elements['image']) and isset($elements['image']['enabled']) and $elements['image']['enabled']) ? $this->image() : '';
$gallery = (isset($elements['gallery']) and isset($elements['gallery']['enabled']) and $elements['gallery']['enabled']) ? $this->gallery() : '';
$embeds = (isset($elements['embed']) and isset($elements['embed']['enabled']) and $elements['embed']['enabled']) ? $this->embeds() : '';
$labels = (isset($elements['labels']) and isset($elements['labels']['enabled']) and $elements['labels']['enabled']) ? $this->labels() : '';
$content = (isset($elements['content']) and isset($elements['content']['enabled']) and $elements['content']['enabled']) ? $this->content($this->filtered_content) : '';
$remark = (isset($elements['remark']) and isset($elements['remark']['enabled']) and $elements['remark']['enabled']) ? $this->remark() : '';
$tags = (isset($elements['tags']) and isset($elements['tags']['enabled']) and $elements['tags']['enabled']) ? $this->tags() : '';
$contact_info = (isset($elements['contact']) and isset($elements['contact']['enabled']) and $elements['contact']['enabled']) ? $this->contact_info() : '';
$features = (isset($elements['features']) and isset($elements['features']['enabled']) and $elements['features']['enabled']) ? $this->features() : '';
$attributes = (isset($elements['attributes']) and isset($elements['attributes']['enabled']) and $elements['attributes']['enabled']) ? $this->attributes() : '';
$map = (isset($elements['map']) and isset($elements['map']['enabled']) and $elements['map']['enabled']) ? $this->map() : '';
$owner = (isset($elements['owner']) and isset($elements['owner']['enabled']) and $elements['owner']['enabled']) ? $this->owner() : '';
$abuse = (isset($elements['abuse']) and isset($elements['abuse']['enabled']) and $elements['abuse']['enabled']) ? $this->abuse() : '';
$availability = (isset($elements['availability']) and isset($elements['availability']['enabled']) and $elements['availability']['enabled']) ? $this->availability() : '';
?>
<div class="lsd-row">
    <div class="lsd-col-8">
		
		<div class="lsd-single-image-wrapper">
			<?php 
				if($labels) echo LSD_Kses::element($labels);
				if($image) echo LSD_Kses::element($image);
			?>
		</div>

		<?php if($gallery) echo LSD_Kses::element($gallery); ?>
		<?php if($title) echo LSD_Kses::element($title);  ?>
		
		<?php if($categories) echo LSD_Kses::element($categories); ?>
		<?php if($price) echo LSD_Kses::element($price); ?>
		<?php if($tags) echo LSD_Kses::element($tags);  ?>
			
		<?php if($content) echo LSD_Kses::element($content); ?>
        <?php if($embeds) echo LSD_Kses::rich($embeds); ?>
		<?php if($attributes) echo LSD_Kses::element($attributes); ?>
		
		{acf}
		
		<?php if($remark) echo LSD_Kses::element($remark); ?>

        {franchise}
		{auction}
		{stats}
		
		<div class="lsd-single-page-section-map-top">
			<?php if($locations) echo LSD_Kses::element($locations); ?>
			<?php if($address) echo LSD_Kses::element($address); ?>
		</div>

		<?php if($map) echo LSD_Kses::form($map); ?>
		
        {booking}
        {discussion}
		
		<?php if($share) echo LSD_Kses::element($share); ?>

    </div>
    <div class="lsd-col-4 lsd-single-page-section-right-col">
		
		<?php if($owner) echo LSD_Kses::form($owner); ?>
		<?php if($features) echo LSD_Kses::element($features);  ?>

        {locallogic}
		
		<?php if($availability) echo LSD_Kses::element($availability); ?>
		<?php if($contact_info) echo LSD_Kses::element($contact_info); ?>

        {team}

        <?php if($abuse) echo LSD_Kses::form($abuse); ?>
		
    </div>
</div>