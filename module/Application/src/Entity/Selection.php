<?php
namespace Application\Entity; 

use Doctrine\ORM\Mapping as ORM;

/**
* Этот класс представляет собой Заявка на подбор.
* @ORM\Entity()
* @ORM\Table(name="selections")
*/
class Selection
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
* @ORM\Column(name="name")
*/
protected $name;

/**
* @ORM\Column(name="phone")
*/
protected $phone;

/**
* @ORM\Column(name="message")
*/
protected $message;

/**
* Возвращает ID Заявка на подбора.
* @return integer
*/
public function getId()
{
	return $this->id;
}

/**
* Задает ID Заявка на подбора.
* @param $id
*/
public function setId($id)
{
	$this->id = $id;
}

/**
* Возвращает listing_id Заявка на подбора.
* @return int
*/
public function getListingId()
{
	return $this->listingId;
}

/**
* Задает listing_id Заявка на подбора.
* @param $listing_id
*/
public function setListingId($listing_id)
{
	$this->listingId = $listing_id;
}

/**
* Возвращает name Заявка на подбора.
* @return string
*/
public function getName()
{
	return $this->name;
}

/**
* Задает name Заявка на подбора.
* @param $name
*/
public function setName($name)
{
	$this->name = $name;
}

/**
* Возвращает phone Заявка на подбора.
* @return string
*/
public function getPhone()
{
	return $this->phone;
}

/**
* Задает phone Заявка на подбора.
* @param $phone
*/
public function setPhone($phone)
{
	$this->phone = $phone;
}

/**
* Возвращает message Заявка на подбора.
* @return string
*/
public function getMessage()
{
	return $this->message;
}

/**
* Задает message Заявка на подбора.
* @param $message
*/
public function setMessage($message)
{
	$this->message = $message;
}

}