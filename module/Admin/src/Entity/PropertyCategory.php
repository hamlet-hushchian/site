<?php
namespace Admin\Entity; 

use Doctrine\ORM\Mapping as ORM;

/**
* Этот класс представляет собой Категория недвижимости.
* @ORM\Entity()
* @ORM\Table(name="property_categories")
*/
class PropertyCategory
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
* Возвращает ID Категория недвижимостиа.
* @return integer
*/
public function getId()
{
	return $this->id;
}

/**
* Задает ID Категория недвижимостиа.
* @param $id
*/
public function setId($id)
{
	$this->id = $id;
}

/**
* Возвращает name Категория недвижимостиа.
* @return string
*/
public function getName()
{
	return $this->name;
}

/**
* Задает name Категория недвижимостиа.
* @param $name
*/
public function setName($name)
{
	$this->name = $name;
}

/**
* Возвращает name_lat Категория недвижимостиа.
* @return string
*/
public function getNameLat()
{
	return $this->nameLat;
}

/**
* Задает name_lat Категория недвижимостиа.
* @param $name_lat
*/
public function setNameLat($name_lat)
{
	$this->nameLat = $name_lat;
}

}