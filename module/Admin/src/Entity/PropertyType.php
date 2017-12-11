<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой Тип недвижимости.
 * @ORM\Entity()
 * @ORM\Table(name="property_types")
 */
class PropertyType
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="category_id")
     */
    protected $categoryId;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="name_lat")
     */
    protected $nameLat;

    /**
     * Возвращает ID Тип недвижимостиа.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Задает ID Тип недвижимостиа.
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает category_id Тип недвижимостиа.
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Задает category_id Тип недвижимостиа.
     * @param $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->categoryId = $category_id;
    }

    /**
     * Возвращает name Тип недвижимостиа.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Задает name Тип недвижимостиа.
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Возвращает name_lat Тип недвижимостиа.
     * @return string
     */
    public function getNameLat()
    {
        return $this->nameLat;
    }

    /**
     * Задает name_lat Тип недвижимостиа.
     * @param $name_lat
     */
    public function setNameLat($name_lat)
    {
        $this->nameLat = $name_lat;
    }

}