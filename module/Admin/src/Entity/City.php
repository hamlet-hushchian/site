<?php
namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой город.
 * @ORM\Entity()
 * @ORM\Table(name="cities")
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="name_lat")
     */
    protected $nameLat;

    /**
     * Возвращает ID города.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Задает ID города.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает название города.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Задает название города.
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Возвращает название города латиницей.
     * @return string
     */
    public function getNameLat()
    {
        return $this->nameLat;
    }

    /**
     * Задает название города латиницей.
     * @param string $name
     */
    public function setNameLat($name)
    {
        $this->nameLat = $name;
    }
}