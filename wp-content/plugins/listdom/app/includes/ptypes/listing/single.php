<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_PTypes_Listing_Single')):

/**
 * Listdom Listing Post Types Single Class.
 *
 * @class LSD_PTypes_Listing_Single
 * @version	1.0.0
 */
class LSD_PTypes_Listing_Single extends LSD_PTypes_Listing
{
    protected $pattern;
    public $details_page_options;

    /**
     * @var LSD_Entity_Listing
     */
    public $entity;
    public $style;
    protected $filtered_content;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        // Call the parent constructor
        parent::__construct();

        // Details Page options
        $this->details_page_options = LSD_Options::details_page();
	}

    public function add_comments_support($supports)
    {
        $comments = (isset($this->details_page_options['general']) and isset($this->details_page_options['general']['comments']) and $this->details_page_options['general']['comments']) ? true : false;
        if($comments) $supports[] = 'comments';

        return $supports;
	}

    public function template($template)
    {
        // We're in an embed post
        if(is_embed()) return $template;

        if(is_single() and get_post_type() == LSD_Base::PTYPE_LISTING)
        {
            $theme_template = (isset($this->details_page_options['general']) and isset($this->details_page_options['general']['theme_template']) and trim($this->details_page_options['general']['theme_template'])) ? $this->details_page_options['general']['theme_template'] : NULL;
            if($theme_template)
            {
                $located_template = locate_template($theme_template);

                // Template Found
                if($located_template) $template = $located_template;
            }
        }

        return $template;
    }
    
    public function filter_content($content)
    {
        // It's not singular page of listing
        if(!is_singular($this->PT)) return $content;

        global $post;
        if($post->post_type != $this->PT) return $content;

        // Is it an endpoint?
        if($ep = LSD_Endpoints::is()) return LSD_Endpoints::output($ep);

        // Get Listing Details Content
        return $this->get($content);
    }

    public function get($content)
    {
        // Single Page Pattern
        $this->pattern = LSD_Options::details_page_pattern();

        global $post;

        // Apply Listing Display Options
        if($this->isPro() and isset($this->details_page_options['general']['displ']) and $this->details_page_options['general']['displ'])
        {
            $displ = get_post_meta($post->ID, 'lsd_displ', true);

            $this->details_page_options['general']['style'] = isset($displ['style']) ? $displ['style'] : $this->details_page_options['general']['style'];
            if(isset($displ['elements']) and is_array($displ['elements']) and count($displ['elements']))
            {
                $this->pattern = '';
                foreach($displ['elements'] as $key=>$elm)
                {
                    if(isset($elm['enabled']))
                    {
                        $this->details_page_options['elements'][$key]['enabled'] = $elm['enabled'];
                        if($elm['enabled']) $this->pattern .= '{'.$key.'}';
                    }
                }
            }
        }

        $this->style = isset($this->details_page_options['general']['style']) ? $this->details_page_options['general']['style'] : 'style1';
        $this->filtered_content = $content;

        $this->entity = new LSD_Entity_Listing($post);

        // Filtered Content
        if($filtered = apply_filters('lsd_listing_before_single_content', false, $this)) return $filtered;

        // Update Listing Visits
        $this->entity->update_visits();

        // Trigger Action
        do_action('lsd_listing_visited', $post);

        switch($this->style)
        {
            case 'style2':

                $output = $this->style2();
                break;

            default:

                $output = $this->style1();
                break;
        }

        return $output;
    }

    public function style1()
    {
        // Rendered Listing Content
        $rendered = $this->pattern;
		
		// Abuse
        if(strpos($this->pattern, '{abuse}') !== false)
        {
			$abuse = $this->abuse();
			if(trim($abuse)) $abuse = '<div class="lsd-single-abuse-wrapper">'.$abuse.'</div>';

            $rendered = str_replace('{abuse}', LSD_Kses::form($abuse), $rendered);
        }
		
		// Listing Labels
        if(strpos($this->pattern, '{labels}') !== false)
        {
			$labels = $this->labels();
			if(trim($labels)) $labels = '<div class="lsd-single-labels-wrapper">'.$labels.'</div>';
				
            $rendered = str_replace('{labels}', LSD_Kses::element($labels), $rendered);
        }
		
		// Listing Featured Image
        if(strpos($this->pattern, '{image}') !== false)
        {
            $rendered = str_replace('{image}', LSD_Kses::element($this->image()), $rendered);
        }
		
		// Listing Gallery
        if(strpos($this->pattern, '{gallery}') !== false)
        {
			$gallery = $this->gallery();
            $rendered = str_replace('{gallery}', LSD_Kses::element($gallery), $rendered);
        }

        // Listing Embeds
        if(strpos($this->pattern, '{embed}') !== false)
        {
            $embeds = $this->embeds();
            $rendered = str_replace('{embed}', LSD_Kses::rich($embeds), $rendered);
        }
		
        // Listing Address
        if(strpos($this->pattern, '{address}') !== false)
        {
			$address = $this->address();
            $rendered = str_replace('{address}', LSD_Kses::element($address), $rendered);
        }

        // Listing Locations
        if(strpos($this->pattern, '{locations}') !== false)
        {
			$locations = $this->locations();
			if(trim($locations)) $locations = '<div class="lsd-single-locations-box">'.$locations.'</div>';
			
            $rendered = str_replace('{locations}', LSD_Kses::element($locations), $rendered);
        }

        // Listing Owner
        if(strpos($this->pattern, '{owner}') !== false)
        {
            $owner = $this->owner();
            $rendered = str_replace('{owner}', LSD_Kses::form($owner), $rendered);
        }

        // Listing Attributes
        if(strpos($this->pattern, '{attributes}') !== false)
        {
            $attributes = $this->attributes();
            $rendered = str_replace('{attributes}', LSD_Kses::element($attributes), $rendered);
        }

        // Listing Availability
        if(strpos($this->pattern, '{availability}') !== false)
        {
            $availability = $this->availability();
            $rendered = str_replace('{availability}', LSD_Kses::element($availability), $rendered);
        }

        // Listing Categories
        if(strpos($this->pattern, '{categories}') !== false)
        {
            $categories = $this->categories();
            $rendered = str_replace('{categories}', LSD_Kses::element($categories), $rendered);
        }

        // Post Content
        if(strpos($this->pattern, '{content}') !== false)
        {
			$content = $this->content($this->filtered_content);
            $rendered = str_replace('{content}', LSD_Kses::element($content), $rendered);
        }

        // Remark
        if(strpos($this->pattern, '{remark}') !== false)
        {
			$remark = $this->remark();
            $rendered = str_replace('{remark}', LSD_Kses::element($remark), $rendered);
        }

        // Listing Features
        if(strpos($this->pattern, '{features}') !== false)
        {
            $features = $this->features();
            $rendered = str_replace('{features}', LSD_Kses::element($features), $rendered);
        }

        // Listing Map
        if(strpos($this->pattern, '{map}') !== false)
        {
            $map = $this->map();
            $rendered = str_replace('{map}', LSD_Kses::form($map), $rendered);
        }
		
		// Listing Title
        if(strpos($this->pattern, '{title}') !== false)
        {
			$title = $this->title();
            $rendered = str_replace('{title}', LSD_Kses::element($title), $rendered);
        }
        
        // Listing Price
        if(strpos($this->pattern, '{price}') !== false)
        {
            $rendered = str_replace('{price}', LSD_Kses::element($this->price()), $rendered);
        }

        // Listing Share
        if(strpos($this->pattern, '{share}') !== false)
        {
			$share = $this->share();
            $rendered = str_replace('{share}', LSD_Kses::element($share), $rendered);
        }

        // Listing Tags
        if(strpos($this->pattern, '{tags}') !== false)
        {
            $tags = $this->tags();
            $rendered = str_replace('{tags}', LSD_Kses::element($tags), $rendered);
        }

        // Listing Contact Info
        if(strpos($this->pattern, '{contact}') !== false)
        {
            $contact_info = $this->contact_info();
            $rendered = str_replace('{contact}', LSD_Kses::element($contact_info), $rendered);
        }

        // Wrap the content
        return $this->wrapper($rendered);
    }

    public function style2()
    {
        // Generate Output
        ob_start();
        include lsd_template('single/style2.php');
        $output = ob_get_clean();

        return $this->wrapper($output);
    }

    public function wrapper($content)
    {
		$schema = lsd_schema()->scope()->type(NULL, $this->entity->get_data_category());
        $rendered = '<div class="lsd-single-page-wrapper lsd-font-m lsd-single-'.$this->style.'" '.$schema.'>'.$content.'</div>';

        // Remove remained placeholders
        $rendered = preg_replace('/{.*}/', '', apply_filters('lsd_listing_single_content', $rendered, $this));

        return $rendered;
    }

    public function address()
    {
        $address = $this->entity->get_address(false);
		
        // Don't show anything when there is no Address!
        if(!trim($address)) return '';

        // Schema
        $schema = lsd_schema()->address();

        $output = '';
        if($this->details_page_options['elements']['address']['show_title']) $output .= '<div class="lsd-single-label">'.esc_html__('Address', 'listdom').'</div>';
        $output .= '<div class="lsd-single-element lsd-single-address" '.$schema.'>'.$address.'</div>';

        return $output;
    }

    public function locations()
    {
        $locations = $this->entity->get_locations();

        // Don't show anything when there is no locations!
        if(!trim($locations)) return '';

        $output = '';
        if($this->details_page_options['elements']['locations']['show_title']) $output .= '<div class="lsd-single-label-inline">'.esc_html__('Locations', 'listdom').'</div>';
        $output .= '<div class="lsd-single-element lsd-single-locations">'.$locations.'</div>';

        return $output;
    }

    public function owner()
    {
        $owner = $this->entity->get_owner('details', $this->details_page_options['elements']['owner']);

        // Don't show anything if nothing found
        if(trim($owner) == '') return '';

        $output = '<div class="lsd-single-page-section lsd-single-page-owner">';
        if($this->details_page_options['elements']['owner']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('Author Info', 'listdom').'</h2>';
        $output .= '<div class="lsd-single-owner-box lsd-single-element lsd-single-owner">'.$owner.'</div>';
		$output .= '</div>';
		
        return $output;
    }

    public function abuse()
    {
        $abuse = $this->entity->get_abuse();

        // Don't show anything if nothing found
        if(trim($abuse) == '') return '';

        $output = '<div class="lsd-single-page-section lsd-single-page-abuse">';
        if($this->details_page_options['elements']['abuse']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('Report Abuse', 'listdom').'</h2>';
        $output .= '<div class="lsd-single-abuse-box lsd-single-element lsd-single-abuse">'.$abuse.'</div>';
        $output .= '</div>';

        return $output;
    }

    public function attributes()
    {
        $show_icons = isset($this->details_page_options['elements']['attributes']['show_icons']) ? $this->details_page_options['elements']['attributes']['show_icons'] : 0;
        $attributes = $this->entity->get_attributes($show_icons);

        // Don't show anything when there is no attribute!
        if(!trim($attributes)) return '';

        $output = '<div class="lsd-single-page-section lsd-single-page-attributes">';
        if($this->details_page_options['elements']['attributes']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('Listing Details', 'listdom').'</h2>';
        $output .= '<div class="lsd-single-attributes-box lsd-single-element lsd-single-attributes">'.$attributes.'</div>';
		$output .= '</div>';
		
        return $output;
    }

    public function availability()
    {
        $availability = $this->entity->get_availability();

        // Don't show anything if nothing found
        if(trim($availability) == '') return '';

        $output = '<div class="lsd-single-page-section lsd-single-page-section-availability">';
        if($this->details_page_options['elements']['availability']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('Availability Time', 'listdom').'</h2>';
        $output .= '<div class="lsd-single-availability-box"><div class="lsd-single-element lsd-single-availability">'.$availability.'</div></div>';
		$output .= '</div>';
		
        return $output;
    }

    public function categories($show_color = true,  $color_method = 'bg')
    {
        // Display Main Category or All Categories
        $multiple = apply_filters('lsd_listing_display_multiple_categories', false);

        // Get Categories
        $categories = $this->entity->get_categories($show_color, $multiple, $color_method);

        // Don't show anything when there is no category!
        if(!trim($categories)) return '';

        $output = '';
        if($this->details_page_options['elements']['categories']['show_title']) $output .= '<div class="lsd-single-label-inline"><i class="lsd-icon fa fa-folder-o fa-lg" aria-hidden="true"></i> '.esc_html__('Category', 'listdom').'</div>';
        $output .= '<div class="lsd-single-element lsd-single-category lsd-listing-category">'.$categories.'</div>';

        return $output;
    }

    public function content($content)
    {
        $content = $this->entity->get_content($content);

        // Don't show anything when there is no content!
        if(!trim($content)) return '';

        // Schema
        $schema = lsd_schema()->description();

        $output = '<div class="lsd-single-page-section lsd-single-page-section-content">';
        if($this->details_page_options['elements']['content']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('Description', 'listdom').'</h2>';
        $output .= '<div class="lsd-single-content-wrapper lsd-single-element lsd-single-content" '.$schema.'>'.$content.'</div>';
		$output .= '</div>';

        return $output;
    }

    public function remark()
    {
        $remark = $this->entity->get_remark();

        // Don't show anything when there is no remark!
        if(!trim($remark)) return '';

		$schema = lsd_schema()->scope()->type('http://schema.org/UserComments', NULL);

        $output = '<div class="lsd-single-page-section lsd-single-page-section-remark" '.$schema.'>';
        if($this->details_page_options['elements']['remark']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('Owner Message', 'listdom').'</h2>';
        $output .= '<div class="lsd-single-content-wrapper lsd-single-element lsd-single-remark">'.$remark.'</div>';
		$output .= '</div>';
		
        return $output;
    }

    public function features()
    {
        $show_icons = isset($this->details_page_options['elements']['features']['show_icons']) ? $this->details_page_options['elements']['features']['show_icons'] : 0;
        $features = $this->entity->get_features($show_icons);

        // Don't show anything when there is no features!
        if(!trim($features)) return '';

        $output = '<div class="lsd-single-page-section lsd-single-page-section-features">';
        if($this->details_page_options['elements']['features']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('Features', 'listdom').'</h2>';
        $output .= '<div class=" lsd-single-features-wrapper lsd-single-element lsd-single-features">'.$features.'</div>';
		$output .= '</div>';
		
        return $output;
    }

    public function map()
    {
        $map = $this->entity->get_map(array
        (
            'provider'=>(isset($this->details_page_options['elements']['map']['map_provider']) ? $this->details_page_options['elements']['map']['map_provider'] : LSD_Map_Provider::def()),
            'style'=>(isset($this->details_page_options['elements']['map']['style']) ? $this->details_page_options['elements']['map']['style'] : NULL),
            'gplaces'=>(isset($this->details_page_options['elements']['map']['gplaces']) ? $this->details_page_options['elements']['map']['gplaces'] : 0),
            'mapcontrols'=>array
            (
                'zoom'=>(isset($this->details_page_options['elements']['map']['control_zoom']) ? $this->details_page_options['elements']['map']['control_zoom'] : 'RIGHT_BOTTOM'),
                'maptype'=>(isset($this->details_page_options['elements']['map']['control_maptype']) ? $this->details_page_options['elements']['map']['control_maptype'] : 'TOP_LEFT'),
                'streetview'=>(isset($this->details_page_options['elements']['map']['control_streetview']) ? $this->details_page_options['elements']['map']['control_streetview'] : 'RIGHT_BOTTOM'),
                'scale'=>(isset($this->details_page_options['elements']['map']['control_scale']) ? $this->details_page_options['elements']['map']['control_scale'] : '0'),
                'fullscreen'=>(isset($this->details_page_options['elements']['map']['control_fullscreen']) ? $this->details_page_options['elements']['map']['control_fullscreen'] : '1'),
            ),
            'args' => $this->details_page_options['elements']['map']
        ));

        // Don't show anything when there is no Map!
        if(!trim($map)) return '';

        // Include Map Assets to the page
        LSD_Assets::map();

        $output = '<div class="lsd-single-page-section lsd-single-page-section-map">';
        if($this->details_page_options['elements']['map']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('See on the Map', 'listdom').'</h2>';
        $output .= '<div class="lsd-listing-googlemap lsd-single-element lsd-single-map">'.$map.'</div>';
		$output .= '</div>';
		
        return $output;
    }

    public function image()
    {
        $featured_image = $this->entity->get_featured_image('full');

        // Don't show anything when there is no Featured Image!
        if(!trim($featured_image)) return '';

        $output = '';
        if($this->details_page_options['elements']['image']['show_title']) $output .= '<div class="lsd-single-label">'.esc_html__('Listing Image', 'listdom').'</div>';
        $output .= '<div class="lsd-single-element lsd-single-featured-image" '.lsd_schema()->scope()->type('http://schema.org/ImageObject').'>'.$featured_image.'</div>';

        return $output;
    }

    public function gallery()
    {
        $gallery = $this->entity->get_gallery(array(
            'lightbox' => (isset($this->details_page_options['elements']['gallery']['lightbox']) ? $this->details_page_options['elements']['gallery']['lightbox'] : 1)
        ));

        // Don't show anything when there is no Gallery!
        if(!trim($gallery)) return '';

        $output = '<div class="lsd-single-gallery-box">';
        if($this->details_page_options['elements']['gallery']['show_title']) $output .= '<div class="lsd-single-label">'.esc_html__('Listing Gallery', 'listdom').'</div>';
        $output .= '<div class="lsd-single-element lsd-single-gallery">'.$gallery.'</div>';
		$output .= '</div>';
		
        return $output;
    }

    public function embeds()
    {
        $embeds = $this->entity->get_embeds();

        // Don't show anything when there is no Embeds!
        if(!trim($embeds)) return '';

        $output = '<div class="lsd-single-page-section lsd-single-page-embeds"><div class="lsd-single-embeds-box">';
        if($this->details_page_options['elements']['embed']['show_title']) $output .= '<div class="lsd-single-label">'.esc_html__('Embed Codes', 'listdom').'</div>';
        $output .= '<div class="lsd-single-element lsd-single-embed">'.$embeds.'</div>';
		$output .= '</div></div>';
		
        return $output;
    }

    public function labels()
    {
        $labels = $this->entity->get_labels();

        // Don't show anything when nothing found!
        if(!trim($labels)) return '';

        $output = '';
        if($this->details_page_options['elements']['labels']['show_title']) $output .= '<div class="lsd-single-label">'.esc_html__('Labels', 'listdom').'</div>';
        $output .= '<div class="lsd-single-element lsd-single-labels">'.$labels.'</div>';

        return $output;
    }

    public function price()
    {
        $price = $this->entity->get_price();
		
        // Don't show anything when nothing found!
        if(!trim($price)) return '';

        // Schema
        $schema = lsd_schema()->priceRange();

        $output = '';
        if($this->details_page_options['elements']['price']['show_title']) $output .= '<div class="lsd-single-label">'.esc_html__('Price', 'listdom').'</div>';
        $output .= '<div class="lsd-single-element lsd-single-price lsd-color-m-bg ' . $this->get_text_class() . '" '.$schema.'>'.$price.'</div>';

        return $output;
    }

    public function share()
    {
        $share = $this->entity->get_share_buttons('single');

        // Don't show anything when nothing found!
        if(!trim($share)) return '';

        $output = '<div class="lsd-single-page-section lsd-single-page-section-share">';
        if($this->details_page_options['elements']['share']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('Share', 'listdom').'</h2>';
        $output .= '<div class="lsd-single-share-box lsd-single-element lsd-single-share">'.$share.'</div>';
		$output .= '</div>';
 
        return $output;
    }

    public function tags()
    {
        $tags = $this->entity->get_tags();

        // Don't show anything when there is no tag!
        if(!trim($tags)) return '';

        $output = '<div class="lsd-single-tags-wrapper">';
        if($this->details_page_options['elements']['tags']['show_title']) $output .= '<div class="lsd-single-label-inline"><i class="lsd-icon fa fa-tags fa-lg" aria-hidden="true"></i> <span>'.esc_html__('Tags', 'listdom').'</span></div>';
        $output .= '<div class="lsd-single-element lsd-single-tags">'.$tags.'</div>';
		$output .= '</div>';
		
        return $output;
    }

    public function title($claim = true, $favorite = true)
    {
        $title = $this->entity->get_title();

        // Don't show anything when there is no title!
        if(!trim($title)) return '';

        // Schema
        $schema = lsd_schema()->name();

        $output = '';
        if($this->details_page_options['elements']['title']['show_title']) $output .= '<div class="lsd-single-label">'.esc_html__('Title', 'listdom').'</div>';
		
		// Claim
        if($claim) $output .= LSD_Kses::element($this->entity->get_claim_button());

        // Favorite
        if($favorite) $output .= LSD_Kses::element($this->entity->get_favorite_button('button'));
		
        $output .= '<h1 class="lsd-single-element lsd-single-title" '.$schema.'>'.$title.'</h1>';

        return $output;
    }

    public function contact_info()
    {
        $contact = $this->entity->get_contact_info();

        // Don't show anything when there is no contact information!
        if(!trim($contact)) return '';

        $output = '<div class="lsd-single-page-section lsd-single-page-section-contact">';
        if($this->details_page_options['elements']['contact']['show_title']) $output .= '<h2 class="lsd-single-page-section-title">'.esc_html__('Contact Info', 'listdom').'</h2>';
        $output .= '<div class="lsd-single-element lsd-single-contact-info lsd-single-contact-box">'.$contact.'</div>';
		$output .= '</div>';
		
        return $output;
    }
}

endif;