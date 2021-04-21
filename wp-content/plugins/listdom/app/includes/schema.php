<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('LSD_Schema')):

/**
 * Listdom Schema Class.
 *
 * @class LSD_Schema
 * @version	1.0.0
 */
class LSD_Schema extends LSD_Base
{
    /**
     * @var bool
     */
    public $pro;

    /**
     * @var string
     */
    public $type;

    /**
     * @var array
     */
    private $schema;

    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();

        // Pro
        $this->pro = $this->isPro();

        // Schema
        $this->schema = array();
    }

    public function __toString()
    {
        $string = trim(implode(' ', $this->schema));

        $this->schema = array();
        return $string;
    }

    public function scope()
    {
        if($this->pro) $this->schema[] = 'itemscope';
        return $this;
	}

    /**
     * @param string $type
     * @param WP_Term $category
     * @return $this
     */
    public function type($type = NULL, $category = NULL)
    {
        if($this->pro)
        {
            if(!$type)
            {
                // Category Type
                $t = isset($category->term_id) ? get_term_meta($category->term_id, 'lsd_schema', true) : '';
                if(!trim($t)) $t = 'https://schema.org/LocalBusiness';

                $type = $t;
            }

            $this->schema[] = 'itemtype="'.esc_attr($type).'"';
        }

        return $this;
    }

    public function attr($name, $value)
    {
        if($this->pro) $this->schema[] = $name.'="'.esc_attr($value).'"';
        return $this;
    }

    public function meta($name, $value)
    {
        if($this->pro) $this->schema[] = '<meta itemprop="'.esc_attr($name).'" content="'.esc_attr($value).'">';
        return $this;
    }

    public function prop($name)
    {
        if($this->pro) $this->schema[] = 'itemprop="'.esc_attr($name).'"';
        return $this;
    }

    public function name()
    {
        return $this->prop('name');
    }

    public function url()
    {
        return $this->prop('url');
    }

    public function address()
    {
        return $this->prop('address');
    }

    public function priceRange()
    {
        return $this->prop('priceRange');
    }

    public function telephone()
    {
        return $this->prop('telephone');
    }

    public function email()
    {
        return $this->prop('email');
    }
	
	public function description()
    {
        return $this->prop('description');
    }
	
	public function jobTitle()
    {
        return $this->prop('jobTitle');
    }
	
	public function faxNumber()
    {
        return $this->prop('faxNumber');
    }
	
	public function openingHours()
    {
        return $this->prop('openingHours');
    }
	
	public function category()
    {
        return $this->prop('category');
    }
	
	public function subjectOf()
    {
        return $this->prop('subjectOf');
    }
	
	public function commentText()
    {
        return $this->prop('commentText');
    }
	
	public function associatedMedia()
    {
        return $this->prop('associatedMedia');
    }
}

endif;