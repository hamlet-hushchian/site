<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой Микрорайон.
 * @ORM\Entity()
 * @ORM\Table(name="microdistricts")
 */
class Microdistrict
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="district_id")
     */
    protected $districtId;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\District")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     */
    protected $district;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="name_lat")
     */
    protected $nameLat;

    /**
     * Возвращает ID Микрорайона.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Задает ID Микрорайона.
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает district_id Микрорайона.
     * @return int
     */
    public function getDistrictId()
    {
        return $this->districtId;
    }

    /**
     * Задает district_id Микрорайона.
     * @param $district_id
     */
    public function setDistrictId($district_id)
    {
        $this->districtId = $district_id;
    }

    /**
     * Возвращает district Микрорайона.
     * @return @ORM\Admin\Entity\District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Задает district Микрорайона.
     * @param @ORM\Admin\Entity\District
     */
    public function setDistrict($district)
    {
        $this->district = $district;
    }

    /**
     * Возвращает name Микрорайона.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Задает name Микрорайона.
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Возвращает name_lat Микрорайона.
     * @return string
     */
    public function getNameLat()
    {
        return $this->nameLat;
    }

    /**
     * Задает name_lat Микрорайона.
     * @param $name_lat
     */
    public function setNameLat($name_lat)
    {
        $this->nameLat = $name_lat;
    }

}