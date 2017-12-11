<?php
namespace Admin\Entity; 

use Doctrine\ORM\Mapping as ORM;

/**
* Этот класс представляет собой тип сделки.
* @ORM\Entity()
* @ORM\Table(name="deal_types")
*/
class DealType
{
/**
* @ORM\Id
* @ORM\Column(name="id")
* @ORM\GeneratedValue
*/
protected $id;

/**
* @ORM\Column(name="deal_categories_id")
*/
protected $dealCategoriesId;

/**
* @ORM\Column(name="name")
*/
protected $name;

/**
* @ORM\Column(name="name_lat")
*/
protected $nameLat;

/**
* Возвращает ID тип сделки.
* @return integer
*/
public function getId()
{
	return $this->id;
}

/**
* Задает ID тип сделки.
* @param $id
*/
public function setId($id)
{
	$this->id = $id;
}

/**
* Возвращает deal_categories_id тип сделкиа.
* @return int
*/
public function getDealCategoriesId()
{
	return $this->dealCategoriesId;
}

/**
* Задает deal_categories_id тип сделкиа.
* @param $deal_categories_id
*/
public function setDealCategories($deal_categories_id)
{
	$this->dealCategoriesId = $deal_categories_id;
}

/**
* Возвращает name тип сделкиа.
* @return string
*/
public function getName()
{
	return $this->name;
}

/**
* Задает name тип сделкиа.
* @param $name
*/
public function setName($name)
{
	$this->name = $name;
}

/**
* Возвращает name_lat тип сделкиа.
* @return string
*/
public function getNameLat()
{
	return $this->nameLat;
}

/**
* Задает name_lat тип сделкиа.
* @param $name_lat
*/
public function setNameLat($name_lat)
{
	$this->nameLat = $name_lat;
}

}