<?php
namespace Admin\Entity; 

use Doctrine\ORM\Mapping as ORM;

/**
* Этот класс представляет собой станция метро.
* @ORM\Entity()
* @ORM\Table(name="subway_stations")
*/
class SubwayStation
{
/**
* @ORM\Id
* @ORM\Column(name="id")
* @ORM\GeneratedValue
*/
protected $id;

/**
* @ORM\Column(name="city_id")
*/
protected $cityId;

/**
* @ORM\Column(name="branch_id")
*/
protected $branchId;

/**
* @ORM\Column(name="name")
*/
protected $name;

/**
* @ORM\Column(name="name_lat")
*/
protected $nameLat;

/**
* Возвращает ID станция метроа.
* @return integer
*/
public function getId()
{
	return $this->id;
}

/**
* Задает ID станция метроа.
* @param $id
*/
public function setId($id)
{
	$this->id = $id;
}

/**
* Возвращает city_id станция метроа.
* @return int
*/
public function getCityId()
{
	return $this->cityId;
}

/**
* Задает city_id станция метроа.
* @param $city_id
*/
public function setCityId($city_id)
{
	$this->cityId = $city_id;
}

/**
* Возвращает branch_id станция метроа.
* @return int
*/
public function getBranchId()
{
	return $this->branchId;
}

/**
* Задает branch_id станция метроа.
* @param $branch_id
*/
public function setBranchId($branch_id)
{
	$this->branchId = $branch_id;
}

/**
* Возвращает name станция метроа.
* @return string
*/
public function getName()
{
	return $this->name;
}

/**
* Задает name станция метроа.
* @param $name
*/
public function setName($name)
{
	$this->name = $name;
}

/**
* Возвращает name_lat станция метроа.
* @return string
*/
public function getNameLat()
{
	return $this->nameLat;
}

/**
* Задает name_lat станция метроа.
* @param $name_lat
*/
public function setNameLat($name_lat)
{
	$this->nameLat = $name_lat;
}

}