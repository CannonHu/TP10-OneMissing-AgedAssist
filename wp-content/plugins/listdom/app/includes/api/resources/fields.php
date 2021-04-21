<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_API_Resources_Fields')):

/**
 * Listdom API Fields Resource Class.
 *
 * @class LSD_API_Resources_Fields
 * @version	1.0.0
 */
class LSD_API_Resources_Fields extends LSD_API_Resource
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function grab()
    {
        // Resource
        $resource = new LSD_API_Resource();

        // Taxonomy API Controller
        $taxonomies = new LSD_API_Controllers_Taxonomies();

        $form = array(
            'general' => array(
                'section' => array(
                    'title' => esc_html__('General', 'listdom'),
                ),
                'fields' => array(
                    'title' => array(
                        'key' => 'title',
                        'method' => 'text-input',
                        'label' => esc_html__('Title', 'listdom'),
                        'required' => true,
                    ),
                    'content' => array(
                        'key' => 'content',
                        'method' => 'editor',
                        'label' => esc_html__('Description', 'listdom'),
                        'required' => false,
                    )
                )
            ),
            'category' => array(
                'section' => array(
                    'title' => esc_html__('Category', 'listdom'),
                ),
                'fields' => array(
                    'category' => array(
                        'key' => 'listing_category',
                        'method' => 'dropdown',
                        'label' => esc_html__('Category', 'listdom'),
                        'values' => LSD_API_Resources_Taxonomy::collection($taxonomies->hierarchy(LSD_Base::TAX_CATEGORY, 0, false)),
                        'required' => true,
                    )
                )
            ),
        );

        // Locations
        if(self::is_enabled('locations'))
        {
            $form['locations'] = array(
                'section' => array(
                    'title' => esc_html__('Locations', 'listdom'),
                ),
                'fields' => array(
                    'locations' => array(
                        'key' => 'taxonomies['.LSD_Base::TAX_LOCATION.']',
                        'method' => 'checkboxes',
                        'label' => esc_html__('Locations', 'listdom'),
                        'values' => LSD_API_Resources_Taxonomy::collection($taxonomies->hierarchy(LSD_Base::TAX_LOCATION, 0, false)),
                        'required' => false,
                    ),
                )
            );
        }

        // Tags
        if(self::is_enabled('tags'))
        {
            $form['tags'] = array(
                'section' => array(
                    'title' => esc_html__('Tags', 'listdom'),
                ),
                'fields' => array(
                    'tags' => array(
                        'key' => 'taxonomies['.LSD_Base::TAX_TAG.']',
                        'method' => 'textarea',
                        'label' => esc_html__('Tags', 'listdom'),
                        'placeholder' => esc_html__('Tag1,Tag2,Tag3', 'listdom'),
                        'values' => LSD_API_Resources_Taxonomy::collection($taxonomies->hierarchy(LSD_Base::TAX_TAG, 0, false)),
                        'required' => false,
                    ),
                )
            );
        }

        // Features
        if(self::is_enabled('features'))
        {
            $form['features'] = array(
                'section' => array(
                    'title' => esc_html__('Features', 'listdom'),
                ),
                'fields' => array(
                    'features' => array(
                        'key' => 'taxonomies['.LSD_Base::TAX_FEATURE.']',
                        'method' => 'checkboxes',
                        'label' => esc_html__('Features', 'listdom'),
                        'values' => LSD_API_Resources_Taxonomy::collection($taxonomies->hierarchy(LSD_Base::TAX_FEATURE, 0, false)),
                        'required' => false,
                    ),
                )
            );
        }

        // Labels
        if(self::is_enabled('labels'))
        {
            $form['labels'] = array(
                'section' => array(
                    'title' => esc_html__('Labels', 'listdom'),
                ),
                'fields' => array(
                    'labels' => array(
                        'key' => 'taxonomies['.LSD_Base::TAX_LABEL.']',
                        'method' => 'checkboxes',
                        'label' => esc_html__('Labels', 'listdom'),
                        'values' => LSD_API_Resources_Taxonomy::collection($taxonomies->hierarchy(LSD_Base::TAX_LABEL, 0, false)),
                        'required' => false,
                    ),
                )
            );
        }

        // Featured Image
        if(self::is_enabled('image'))
        {
            $form['image'] = array(
                'section' => array(
                    'title' => esc_html__('Featured Image', 'listdom'),
                ),
                'fields' => array(
                    'featured_image' => array(
                        'key' => 'featured_image',
                        'method' => 'file',
                        'label' => esc_html__('Featured Image', 'listdom'),
                        'required' => false,
                        'developer' => esc_html__('You should upload image and send the attachment ID of image!', 'listdom'),
                    ),
                )
            );
        }

        // Address & Map
        if(self::is_enabled('address'))
        {
            $form['address'] = array(
                'section' => array(
                    'title' => esc_html__('Address / Map', 'listdom'),
                ),
                'fields' => array(
                    'address' => array(
                        'keys' => array(
                            'address',
                            'latitude',
                            'longitude',
                            'object_type',
                            'zoomlevel',
                            'shape_type',
                            'shape_paths',
                            'shape_radius',
                        ),
                        'method' => 'embed',
                        'label' => esc_html__('Address / Map', 'listdom'),
                        'required' => false,
                        'developer' => 'GET /listings/<id>/map-upsert',
                    ),
                )
            );
        }

        // Price
        if(self::is_enabled('price'))
        {
            $currencies = array();
            foreach(LSD_Base::get_currencies() as $symbol=>$currency) $currencies[$currency] = $symbol;

            $form['price'] = array(
                'section' => array(
                    'title' => esc_html__('Price Options', 'listdom'),
                ),
                'fields' => array(
                    'currency' => array(
                        'key' => 'currency',
                        'method' => 'dropdown',
                        'label' => esc_html__('Currency', 'listdom'),
                        'values' => $currencies,
                        'required' => false,
                    ),
                    'price' => array(
                        'key' => 'price',
                        'method' => 'text-input',
                        'label' => esc_html__('Price', 'listdom'),
                        'required' => false,
                    ),
                    'price_max' => array(
                        'key' => 'price_max',
                        'method' => 'text-input',
                        'label' => esc_html__('Price (Max)', 'listdom'),
                        'required' => false,
                    ),
                    'price_after' => array(
                        'key' => 'price_after',
                        'method' => 'text-input',
                        'label' => esc_html__('Price Description', 'listdom'),
                        'required' => false,
                    ),
                )
            );
        }

        // Availability
        if(self::is_enabled('availability'))
        {
            $ava_fields = array();
            foreach(LSD_Main::get_weekdays() as $weekday)
            {
                $daycode = $weekday['code'];
                $ava_fields['ava_'.$daycode.'_hour'] = array(
                    'key' => 'ava['.$daycode.'][hours]',
                    'method' => 'text-input',
                    'label' => esc_html__($weekday['day'], 'listdom'),
                    'placeholder' => esc_html__('9 - 18, 9 AM to 9 PM', 'listdom'),
                    'required' => false,
                );

                $ava_fields['ava_'.$daycode.'_off'] = array(
                    'key' => 'ava['.$daycode.'][off]',
                    'method' => 'checkbox',
                    'label' => esc_html__('Off', 'listdom'),
                    'values' => array(0, 1),
                    'required' => false,
                );
            }

            $form['availability'] = array(
                'section' => array(
                    'title' => esc_html__('Work Hours', 'listdom'),
                ),
                'fields' => $ava_fields
            );
        }

        // Contact
        if(self::is_enabled('contact'))
        {
            $form['contact'] = array(
                'section' => array(
                    'title' => esc_html__('Contact Details', 'listdom'),
                ),
                'fields' => array(
                    'email' => array(
                        'key' => 'email',
                        'method' => 'email-input',
                        'label' => esc_html__('Email', 'listdom'),
                        'placeholder' => esc_html__('Email', 'listdom'),
                        'required' => false,
                    ),
                    'phone' => array(
                        'key' => 'phone',
                        'method' => 'tel-input',
                        'label' => esc_html__('Phone', 'listdom'),
                        'placeholder' => esc_html__('Phone', 'listdom'),
                        'required' => false,
                    ),
                    'link' => array(
                        'key' => 'link',
                        'method' => 'url-input',
                        'label' => esc_html__('Listing Link', 'listdom'),
                        'placeholder' => 'http://anothersite.com/listing-page/',
                        'guide' => esc_html__('If you fill it, then it will be used to override default details page link. You can use it for linking the listing to an external or custom page!', 'listdom'),
                        'required' => false,
                    ),
                )
            );
        }

        // Remark
        if(self::is_enabled('remark'))
        {
            $form['remark'] = array(
                'section' => array(
                    'title' => esc_html__('Remark', 'listdom'),
                ),
                'fields' => array(
                    'remark' => array(
                        'key' => 'remark',
                        'method' => 'textarea',
                        'label' => esc_html__('Owner Message', 'listdom'),
                        'placeholder' => esc_html__('Owner message to the visitors ...', 'listdom'),
                        'required' => false,
                    ),
                )
            );
        }

        // Gallery
        if(self::is_enabled('gallery'))
        {
            $form['gallery'] = array(
                'section' => array(
                    'title' => esc_html__('Gallery', 'listdom'),
                ),
                'fields' => array(
                    'gallery' => array(
                        'key' => 'gallery[]',
                        'method' => 'file',
                        'label' => esc_html__('Gallery', 'listdom'),
                        'required' => false,
                        'developer' => esc_html__('You should upload images and send an array of attachment IDs!', 'listdom'),
                    ),
                )
            );
        }

        // Embed
        if(self::is_enabled('embed'))
        {
            $form['embeds'] = array(
                'section' => array(
                    'title' => esc_html__('Embed Codes', 'listdom'),
                ),
                'fields' => array(
                    'embeds_name' => array(
                        'key' => 'embeds[][name]',
                        'method' => 'text-input',
                        'label' => esc_html__('Title', 'listdom'),
                        'placeholder' => esc_html__('Title', 'listdom'),
                        'required' => false,
                    ),
                    'embeds_code' => array(
                        'key' => 'embeds[][code]',
                        'method' => 'textarea',
                        'label' => esc_html__('Code', 'listdom'),
                        'placeholder' => esc_html__('Code', 'listdom'),
                        'required' => false,
                    ),
                )
            );
        }

        // Attributes
        if(self::is_enabled('attributes') and $resource->isPro())
        {
            $t = new LSD_Taxonomies_Attribute();
            $terms = $t->get_terms();

            $attributes = array();
            foreach($terms as $term)
            {
                $type = get_term_meta($term->term_id, 'lsd_field_type', true);
                if(in_array($type, array('text', 'number', 'email', 'url'))) $type = $type.'-input';

                // Get all category status
                $all_categories = get_term_meta($term->term_id, 'lsd_all_categories', true);
                if(trim($all_categories) == '') $all_categories = 1;

                // Get specific categories
                $categories = get_term_meta($term->term_id, 'lsd_categories', true);
                if($all_categories or !is_array($categories)) $categories = array();

                $values = array();
                $values_str = get_term_meta($term->term_id, 'lsd_values', true);
                if(trim($values_str)) foreach(explode(',', trim($values_str, ', ')) as $value) $values[$value] = $value;

                $attributes[$term->term_id] = array(
                    'key' => 'attributes['.$term->term_id.']',
                    'method' => $type,
                    'label' => $term->name,
                    'values' => $values,
                    'all_categories' => $all_categories,
                    'categories' => array_keys($categories),
                    'required' => false,
                );
            }

            $form['attributes'] = array(
                'section' => array(
                    'title' => esc_html__('Attributes', 'listdom'),
                ),
                'fields' => $attributes
            );
        }

        // Listdom Options
        $settings = LSD_Options::settings();

        // Guest Fields
        if(isset($settings['submission_guest']) and $settings['submission_guest'])
        {
            $form['guest'] = array(
                'section' => array(
                    'title' => esc_html__('To Reviewer', 'listdom'),
                    'guest' => true,
                ),
                'fields' => array(
                    'guest_email' => array(
                        'key' => 'guest_email',
                        'method' => 'email-input',
                        'label' => esc_html__('Email', 'listdom'),
                        'placeholder' => esc_html__('Your Email', 'listdom'),
                        'required' => true,
                    ),
                    'guest_message' => array(
                        'key' => 'guest_message',
                        'method' => 'textarea',
                        'label' => esc_html__('Message', 'listdom'),
                        'placeholder' => esc_html__('Message to Reviewer', 'listdom'),
                        'required' => false,
                    ),
                )
            );
        }

        return apply_filters('lsd_api_resource_fields', $form);
	}

    public static function is_enabled($module)
    {
        // Listdom Options
        $settings = LSD_Options::settings();

        // Option not Found!
        if(!isset($settings['submission_module'])) return true;

        // Module not Found!
        if(isset($settings['submission_module']) and !isset($settings['submission_module'][$module])) return true;

        // Module is disabled
        if(isset($settings['submission_module']) and isset($settings['submission_module'][$module]) and !$settings['submission_module'][$module]) return false;

        // Module is enabled only for admin and editor
        if(isset($settings['submission_module']) and isset($settings['submission_module'][$module]) and $settings['submission_module'][$module] == 2 and !current_user_can('edit_others_pages')) return false;

        return true;
    }
}

endif;