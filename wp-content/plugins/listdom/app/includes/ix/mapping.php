<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_IX_Mapping')):

/**
 * Listdom IX Mapping Class.
 *
 * @class LSD_IX_Mapping
 * @version	1.0.0
 */
class LSD_IX_Mapping extends LSD_IX
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public function listdom_fields()
    {
        // Default Value
        $default = new LSD_IX_Mapping_Default();

        // Attributes
        $attr = new LSD_Taxonomies_Attribute();
        $attributes = $attr->get_terms();

        $fields = array(
            'unique_id' => array(
                'label' => esc_html__('Unique ID', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("It's required if you want to update the listings later. If you don't map it then listdom tries to update the existing listing with same title and content!", 'listdom'),
                'default' => false,
            ),
            'post_title' => array(
                'label' => esc_html__('Listing Title', 'listdom'),
                'type' => 'text',
                'mandatory' => true,
                'default' => false,
            ),
            'post_content' => array(
                'label' => esc_html__('Listing Content', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'default' => false,
            ),
            'post_date' => array(
                'label' => esc_html__('Listing Date', 'listdom'),
                'type' => 'date',
                'mandatory' => false,
                'description' => esc_html__("A date field should get mapped.", 'listdom'),
                'default' => array($default, 'date'),
            ),
            'post_author' => array(
                'label' => esc_html__('Listing Owner', 'listdom'),
                'type' => 'email',
                'mandatory' => false,
                'description' => esc_html__("An email field should get mapped. If mapped then listdom will create a user if not exists and assign the listing to the user.", 'listdom'),
                'default' => array($default, 'email'),
            ),
            'lsd_price' => array(
                'label' => esc_html__('Price', 'listdom'),
                'type' => 'number',
                'mandatory' => false,
                'description' => esc_html__("A numeric field should get mapped.", 'listdom'),
                'default' => array($default, 'number'),
            ),
            'lsd_price_max' => array(
                'label' => esc_html__('Price Max', 'listdom'),
                'type' => 'number',
                'mandatory' => false,
                'description' => esc_html__("A numeric field should get mapped.", 'listdom'),
                'default' => array($default, 'number'),
            ),
            'lsd_price_after' => array(
                'label' => esc_html__('Price After', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A text field should get mapped.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            'lsd_currency' => array(
                'label' => esc_html__('Currency', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A text field should get mapped.", 'listdom'),
                'default' => array($default, 'currency'),
            ),
            'lsd_address' => array(
                'label' => esc_html__('Listing Address', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A text field should get mapped.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            'lsd_latitude' => array(
                'label' => esc_html__('Listing Latitude', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A latitude field should get mapped.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            'lsd_longitude' => array(
                'label' => esc_html__('Listing Longitude', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A longitude field should get mapped.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            'lsd_link' => array(
                'label' => esc_html__('Listing Link', 'listdom'),
                'type' => 'url',
                'mandatory' => false,
                'description' => esc_html__("A URL field should get mapped.", 'listdom'),
                'default' => array($default, 'url'),
            ),
            'lsd_email' => array(
                'label' => esc_html__('Listing Email', 'listdom'),
                'type' => 'email',
                'mandatory' => false,
                'description' => esc_html__("An email field should get mapped.", 'listdom'),
                'default' => array($default, 'email'),
            ),
            'lsd_phone' => array(
                'label' => esc_html__('Listing Phone', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("An phone field should get mapped.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            'lsd_website' => array(
                'label' => esc_html__('Listing Website', 'listdom'),
                'type' => 'url',
                'mandatory' => false,
                'description' => esc_html__("A URL field should get mapped.", 'listdom'),
                'default' => array($default, 'url'),
            ),
            'lsd_remark' => array(
                'label' => esc_html__('Listing Remark', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("An text field should get mapped.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            'lsd_image' => array(
                'label' => esc_html__('Featured Image', 'listdom'),
                'type' => 'url',
                'mandatory' => false,
                'description' => esc_html__("A URL field should get mapped. It should contain image URL.", 'listdom'),
                'default' => array($default, 'url'),
            ),
            'lsd_gallery' => array(
                'label' => esc_html__('Listing Gallery', 'listdom'),
                'type' => 'url',
                'mandatory' => false,
                'description' => esc_html__("A URL field should get mapped. It should contain URLs to images.", 'listdom'),
                'default' => array($default, 'url'),
            ),
            LSD_Base::TAX_CATEGORY => array(
                'label' => esc_html__('Listing Category', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A text field should get mapped. Listdom will create a category using the text if not exists and assign listing to that category.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            LSD_Base::TAX_LOCATION => array(
                'label' => esc_html__('Listing Locations', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A text field should get mapped. Listdom will create locations using the text if not exists and assign listing to locations.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            LSD_Base::TAX_TAG => array(
                'label' => esc_html__('Listing Tags', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A text field should get mapped. Listdom will create tags using the text if not exists and assign listing to tags.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            LSD_Base::TAX_FEATURE => array(
                'label' => esc_html__('Listing Features', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A text field should get mapped. Listdom will create features using the text if not exists and assign listing to features.", 'listdom'),
                'default' => array($default, 'text'),
            ),
            LSD_Base::TAX_LABEL => array(
                'label' => esc_html__('Listing Labels', 'listdom'),
                'type' => 'text',
                'mandatory' => false,
                'description' => esc_html__("A text field should get mapped. Listdom will create labels using the text if not exists and assign listing to labels.", 'listdom'),
                'default' => array($default, 'text'),
            ),
        );

        foreach($attributes as $attribute)
        {
            $type = get_term_meta($attribute->term_id, 'lsd_field_type', true);
            if(in_array($type, array('separator'))) continue;

            $fields['lsd_attribute_'.$attribute->term_id] = array(
                'label' => $attribute->name,
                'type' => (in_array($type, array('number', 'email', 'url')) ? $type : 'text'),
                'mandatory' => false,
                'default' => array($default, (in_array($type, array('number', 'email', 'url')) ? $type : 'text')),
            );
        }

        // Apply Filters
        return apply_filters('lsd_ix_listdom_fields', $fields);
	}

    public function feed_fields($file)
    {
        $ex = explode('.', $file);
        $extension = strtolower(end($ex));

        $fields = array();
        switch($extension)
        {
            case 'csv':

                $fh = fopen($file, 'r');
                $delimiter = $this->delimiter($file);

                while(($row = fgetcsv($fh, 0, $delimiter)) !== false)
                {
                    $fields = array();
                    foreach($row as $k => $v) $fields[$k] = utf8_encode($this->unbom($v));

                    break;
                }

                fclose($fh);
                break;

            default:
                return $fields;
        }

        return $fields;
	}

    public function map(Array $raw, Array $mappings)
    {
        $mapped = array();
        foreach($mappings as $key => $mapping)
        {
            $field = (isset($mapping['map']) and trim($mapping['map']) != '') ? $mapping['map'] : NULL;
            $default = (isset($mapping['default']) and trim($mapping['default']) != '') ? $mapping['default'] : NULL;

            // Not Mapped
            if(is_null($field) and is_null($default)) continue;

            // Value
            $value = (!is_null($field) and isset($raw[$field]) and trim($raw[$field]) != '') ? $raw[$field] : $default;

            // Normalize the Value
            $value = !preg_match('!!u', $value) ? utf8_encode($value) : $value;

            // Add to Mapped Data
            $mapped[$key] = $value;
        }

        // Latitude & Longitude by Address
        if((!isset($mapped['lsd_latitude']) or !isset($mapped['lsd_longitude'])) and isset($mapped['lsd_address']) and trim($mapped['lsd_address']))
        {
            $main = new LSD_Main();
            $geopoint = $main->geopoint($mapped['lsd_address']);

            if(isset($geopoint[0]) and $geopoint[0] and isset($geopoint[1]) and $geopoint[1])
            {
                $mapped['lsd_latitude'] = $geopoint[0];
                $mapped['lsd_longitude'] = $geopoint[1];
            }
        }

        return $mapped;
    }

    public function unbom($text)
    {
        $bom = pack('H*','EFBBBF');

        $text = str_replace("\xEF\xBB\xBF",'', $text);
        return preg_replace("/^$bom/", '', $text);
    }

    public function delimiter($csv)
    {
        $delimiters = array(";" => 0, "," => 0, "\t" => 0, "|" => 0);

        $handle = fopen($csv, 'r');
        $firstLine = fgets($handle);
        fclose($handle);

        foreach($delimiters as $delimiter => &$count)
        {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }
}

endif;