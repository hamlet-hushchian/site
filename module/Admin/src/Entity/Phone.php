<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой телефон.
 * @ORM\Entity()
 * @ORM\Table(name="listing_phones")
 */
class Phone
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
     * @ORM\Column(name="number")
     */
    protected $number;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\Listing", inversedBy="phone")
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
        $listing->addPhone($this);
    }

    /**
     * Возвращает ID телефона.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Задает ID телефона.
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает listing_id телефона.
     * @return int
     */
    public function getListingId()
    {
        return $this->listingId;
    }

    /**
     * Задает listing_id телефона.
     * @param $listing_id
     */
    public function setListingId($listing_id)
    {
        $this->listingId = $listing_id;
    }

    /**
     * Возвращает number телефона.
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Задает number телефона.
     * @param $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

}