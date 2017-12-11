<?php
namespace Application\Entity; 

use Doctrine\ORM\Mapping as ORM;

/**
* Этот класс представляет собой Контактное письмо.
* @ORM\Entity()
* @ORM\Table(name="contact_letters")
*/
class ContactLetter
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
* @ORM\Column(name="phone")
*/
protected $phone;

/**
* @ORM\Column(name="message")
*/
protected $message;

/**
* Возвращает ID Контактное письмоа.
* @return integer
*/
public function getId()
{
	return $this->id;
}

/**
* Задает ID Контактное письмоа.
* @param $id
*/
public function setId($id)
{
	$this->id = $id;
}

/**
* Возвращает name Контактное письмоа.
* @return string
*/
public function getName()
{
	return $this->name;
}

/**
* Задает name Контактное письмоа.
* @param $name
*/
public function setName($name)
{
	$this->name = $name;
}

/**
* Возвращает phone Контактное письмоа.
* @return string
*/
public function getPhone()
{
	return $this->phone;
}

/**
* Задает phone Контактное письмоа.
* @param $phone
*/
public function setPhone($phone)
{
	$this->phone = $phone;
}

/**
* Возвращает message Контактное письмоа.
* @return string
*/
public function getMessage()
{
	return $this->message;
}

/**
* Задает message Контактное письмоа.
* @param $message
*/
public function setMessage($message)
{
	$this->message = $message;
}

}