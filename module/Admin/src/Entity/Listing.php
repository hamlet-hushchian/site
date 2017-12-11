<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Этот класс представляет собой объявление.
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="\Admin\Repository\ListingRepository")
 * @ORM\Table(name="listings")
 */
class Listing
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    // @ORM\Entity(repositoryClass="\Admin\Repository\ListingRepository")

    /**
     * @ORM\Column(name="date_add")
     */
    protected $dateAdd;

    /**
     * @ORM\Column(name="date_edit")
     */
    protected $dateEdit;

    /**
     * @ORM\Column(name="date_call")
     */
    protected $dateCall;

    /**
     * @ORM\Column(name="user_id")
     */
    protected $userId;

    /**
     * @ORM\Column(name="deal_type_id")
     */
    protected $dealTypeId;

    /**
     * Many Listings have One DealType.
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\DealType")
     * @ORM\JoinColumn(name="deal_type_id", referencedColumnName="id")
     */
    private $dealType;

    /**
     * @ORM\Column(name="property_type_id")
     */
    protected $propertyTypeId;

    /**
     * @param mixed $property_type_id
     */
    public function setPropertyTypeId($property_type_id)
    {
        $this->propertyTypeId = $property_type_id;
    }

    /**
     * @return int
     */
    public function getPropertyTypeId()
    {
        return $this->propertyTypeId;
    }

    /**
     * @ORM\Column(name="microdistrict_id")
     */
    protected $microdistrictId;

    /**
     * Many Listings have One Microdistrict.
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\Microdistrict")
     * @ORM\JoinColumn(name="microdistrict_id", referencedColumnName="id")
     */
    protected $microdistrict;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\SubwayStation")
     * @ORM\JoinColumn(name="subway_station_id", referencedColumnName="id")
     */
    protected $subwayStation;

    /**
     * @ORM\Column(name="street")
     */
    protected $street;

    /**
     * @ORM\Column(name="house_number")
     */
    protected $houseNumber;

    /**
     * @ORM\Column(name="price")
     */
    protected $price;

    /**
     * @ORM\Column(name="currency_id")
     */
    protected $currencyId;

    /**
     * Many Listings have One Currency.
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;

    /**
     * Many Listings have One PropertyType.
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\PropertyType")
     * @ORM\JoinColumn(name="property_type_id", referencedColumnName="id")
     */
    private $propertyType;

    /**
     * @ORM\Column(name="description")
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="\Admin\Entity\Phone", mappedBy="listing")
     */
    protected $phones;

    /**
     * @ORM\OneToMany(targetEntity="\Admin\Entity\ListingImage", mappedBy="listing")
     * @ORM\OrderBy({"order" = "ASC"})
     */
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="\Admin\Entity\PropertyParamsValue", mappedBy="listing")
     * @ORM\OrderBy({"paramId" = "ASC"})
     */
    protected $paramsValue;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->phones = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->paramsValue = new ArrayCollection();
    }

    public function getPhones()
    {
        return $this->phones;
    }

    public function addPhone($phone)
    {
        $this->phones[] = $phone;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function addImage($image)
    {
        $this->images[] = $image;
    }

    public function getParamsValue()
    {
        return $this->paramsValue;
    }

    public function addParamValue($paramValue)
    {
        $this->paramsValue[] = $paramValue;
    }


    /**
     * Возвращает ID объявлениеа.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

//    /**
//     * Задает ID объявлениеа.
//     * @param $id
//     */
//    public function setId($id)
//    {
//        $this->id = $id;
//    }

    /**
     * Возвращает date_add объявлениеа.
     * @return string
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Задает date_add объявлениеа.
     * @param $date_add
     */
    public function setDateAdd($date_add)
    {
        $this->dateAdd = $date_add;
    }

    /**
     * Возвращает date_edit объявлениеа.
     * @return string
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }

    /**
     * Задает date_edit объявлениеа.
     * @param $date_edit
     */
    public function setDateEdit($date_edit)
    {
        $this->dateEdit = $date_edit;
    }

    /**
     * Возвращает date_call объявлениеа.
     * @return string
     */
    public function getDateCall()
    {
        return $this->dateCall;
    }

    /**
     * Задает date_call объявлениеа.
     * @param $date_call
     */
    public function setDateCall($date_call)
    {
        $this->dateCall = $date_call;
    }

    /**
     * Возвращает user_id объявлениеа.
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Задает user_id объявлениеа.
     * @param $user_id
     */
    public function setUserId($user_id)
    {
        $this->userId = $user_id;
    }

    /**
     * Возвращает deal_type_id объявлениеа.
     * @return int
     */
    public function getDealTypeId()
    {
        return $this->dealTypeId;
    }

    /**
     * Задает deal_type_id объявлениеа.
     * @param $deal_type_id
     */
    public function setDealTypeId($deal_type_id)
    {
        $this->dealTypeId = $deal_type_id;
    }

    /**
     * Возвращает deal_type объявлениеа.
     * @return @ORM\Admin\Entity\DealType
     */
    public function getDealType()
    {
        return $this->dealType;
    }

    /**
     * Задает deal_type объявлениеа.
     * @param @ORM\Admin\Entity\DealType
     */
    public function setDealType($deal_type)
    {
        $this->dealType = $deal_type;
    }

    /**
     * Возвращает property_type_id объявления.
     * @return @ORM\Admin\Entity\PropertyType;
     */
    public function getPropertyType()
    {
        return $this->propertyType;
    }

    /**
     * Задает property_type_id объявлениеа.
     * @param @ORM\Admin\Entity\PropertyType;
     */
    public function setPropertyType($property_type)
    {
        $this->propertyType = $property_type;
    }

    /**
     * Возвращает microdistrict_id объявления.
     * @return int
     */
    public function getMicrodistrictId()
    {
        return $this->microdistrictId;
    }

    /**
     * Задает microdistrict_id объявлениеа.
     * @param $microdistrict_id
     */
    public function setMicrodistrictId($microdistrict_id)
    {
        $this->microdistrictId = $microdistrict_id;
    }

    /**
     * Возвращает microdistrict объявления.
     * @return @ORM\Admin\Entity\Microdistrict;
     */
    public function getMicrodistrict()
    {
        return $this->microdistrict;
    }

    /**
     * Задает microdistrict объявлениеа.
     * @param @ORM\Admin\Entity\Microdistrict;
     */
    public function setMicrodistrict($microdistrict)
    {
        $this->microdistrict = $microdistrict;
    }

    /**
     * Возвращает subway_station_id объявлениеа.
     * @return @ORM\Admin\Entity\SubwayStation;
     */
    public function getSubwayStation()
    {
        return $this->subwayStation;
    }

    /**
     * Задает subway_station объявлениеа.
     * @param @ORM\Admin\Entity\SubwayStation;
     */
    public function setSubwayStation($subway_station)
    {
        $this->subwayStation = $subway_station;
    }

    /**
     * Возвращает street объявлениеа.
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Задает street объявлениеа.
     * @param $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * Возвращает house_number объявлениеа.
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * Задает house_number объявлениеа.
     * @param $house_number
     */
    public function setHouseNumber($house_number)
    {
        $this->houseNumber = $house_number;
    }

    /**
     * Возвращает price объявлениеа.
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Задает price объявлениеа.
     * @param $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Возвращает currencyId объявлениеа.
     * @return int
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * Задает currencyId объявлениеа.
     * @param $currency_id
     */
    public function setCurrencyId($currency_id)
    {
        $this->currencyId = $currency_id;
    }

    /**
     * Задает валюту.
     * @param @ORM\Admin\Entity\Currency;
     */
    public function SetCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Возвращает связанную cущность объявления - currency.
     * @return @ORM\Admin\Entity\Currency;
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Возвращает description объявлениеа.
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Задает description объявлениеа.
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

}