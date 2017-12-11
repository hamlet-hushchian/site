<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой Фото.
 * @ORM\Entity()
 * @ORM\Table(name="listing_images")
 */
class ListingImage
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="uniq_id")
     */
    protected $uniqId;

    /**
     * @ORM\Column(name="listing_id")
     */
    protected $listingId;

    /**
     * @ORM\Column(name="source_link")
     */
    protected $sourceLink;

    /**
     * @ORM\Column(name="thumb_link")
     */
    protected $thumbLink;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="ext")
     */
    protected $ext;

    /**
     * @ORM\Column(name="crop")
     */
    protected $crop;

    /**
     * @ORM\Column(name="odr")
     */
    protected $order;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\Listing", inversedBy="listingImage")
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
        $listing->addImage($this);
    }

    /**
     * Возвращает ID Фотоа.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Возвращает ID Фотоа.
     * @return integer
     */
    public function getUniqId()
    {
        return $this->uniqId;
    }

    /**
     * Задает ID Фотоа.
     * @param $uniq_id
     */
    public function setUniqId($uniq_id)
    {
        $this->uniqId = $uniq_id;
    }

    /**
     * Возвращает listing_id Фотоа.
     * @return int
     */
    public function getListingId()
    {
        return $this->listingId;
    }

    /**
     * Задает listing_id Фотоа.
     * @param $listing_id
     */
    public function setListingId($listing_id)
    {
        $this->listingId = $listing_id;
    }

    /**
     * Возвращает source_link Фотоа.
     * @return string
     */
    public function getSourceLink()
    {
        return $this->sourceLink;
    }

    /**
     * Задает source_link Фотоа.
     * @param $source_link
     */
    public function setSourceLink($source_link)
    {
        $this->sourceLink = $source_link;
    }

    /**
     * Возвращает thumb_link Фотоа.
     * @return string
     */
    public function getThumbLink()
    {
        return $this->thumbLink;
    }

    /**
     * Задает thumb_link Фотоа.
     * @param $thumb_link
     */
    public function setThumbLink($thumb_link)
    {
        $this->thumbLink = $thumb_link;
    }


    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }


    public function getExt()
    {
        return $this->ext;
    }

    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    public function getCrop()
    {
        return $this->crop;
    }

    public function setCrop($crop)
    {
        $this->crop = $crop;
    }
    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

}