<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* Этот класс представляет собой значения.
* @ORM\Entity()
* @ORM\Table(name="property_params_value")
*/
class PropertyParamsValue
{
/**
* @ORM\Id
* @ORM\Column(name="id")
* @ORM\GeneratedValue
*/
protected $id;

/**
* @ORM\Column(name="listing_id")
*/
protected $listingId;

/**
* @ORM\Column(name="param_id")
*/
protected $paramId;

/**
* @ORM\Column(name="value")
*/
protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\Listing", inversedBy="propertyParamsValue")
     * @ORM\JoinColumn(name="listing_id", referencedColumnName="id")
     */
    protected $listing;

    /*
     * Возвращает связанное объявление.
     * @return \Admin\Entity\Listing
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * Задает связанное объявление.
     * @param \Admin\Entity\Listing $listing
     */
    public function setListing($listing)
    {
        $this->listing = $listing;
        $listing->addParamValue($this);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\PropertyParams", inversedBy="propertyParamsValue")
     * @ORM\JoinColumn(name="param_id", referencedColumnName="id")
     */
    protected $param;

    /*
     * Возвращает связанный параметр.
     * @return \Admin\Entity\PropertyParams
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Задает связанный параметр.
     * @param @ORM\Admin\Entity\PropertyParams
     */
    public function setParam($param)
    {
        $this->param = $param;
        $param->setValue($this);
    }

/**
* Возвращает ID значенияа.
* @return integer
*/
public function getId()
{
	return $this->id;
}

/**
* Задает ID значенияа.
* @param $id
*/
public function setId($id)
{
	$this->id = $id;
}

/**
* Возвращает listing_id значенияа.
* @return int
*/
public function getListingId()
{
	return $this->listingId;
}

/**
* Задает listing_id значенияа.
* @param $listing_id
*/
public function setListingId($listing_id)
{
	$this->listingId = $listing_id;
}

/**
* Возвращает param_id значенияа.
* @return int
*/
public function getParamId()
{
	return $this->paramId;
}

/**
* Задает param_id значенияа.
* @param $param_id
*/
public function setParamId($param_id)
{
	$this->paramId = $param_id;
}

/**
* Возвращает value значенияа.
* @return string
*/
public function getValue()
{
	return $this->value;
}

/**
* Задает value значенияа.
* @param $value
*/
public function setValue($value)
{
	$this->value = $value;
}

}