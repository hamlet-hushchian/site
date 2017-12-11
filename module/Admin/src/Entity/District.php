<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой район.
 * @ORM\Entity()
 * @ORM\Table(name="districts")
 */
class District
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    protected $city;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="name_lat")
     */
    protected $nameLat;

    /**
     * Возвращает ID района.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Задает ID района.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает город.
     * @return @ORM\Admin\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Задает город.
     * @param @ORM\Admin\Entity\City
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Возвращает название района.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Задает название района.
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Возвращает название района латиницей.
     * @return string
     */
    public function getNameLat()
    {
        return $this->nameLat;
    }

    /**
     * Задает название района латиницей.
     * @param string $name
     */
    public function setNameLat($name)
    {
        $this->nameLat = $name;
    }
}