<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой Валюта.
 * @ORM\Entity()
 * @ORM\Table(name="currencies")
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="short")
     */
    protected $short;

    /**
     * @ORM\Column(name="sign")
     */
    protected $sign;

    /**
     * Возвращает ID Валютаа.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Задает ID Валютаа.
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает short Валютаа.
     * @return string
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * Задает short Валютаа.
     * @param $short
     */
    public function setShort($short)
    {
        $this->short = $short;
    }

    /**
     * Возвращает sign Валютаа.
     * @return string
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Задает sign Валютаа.
     * @param $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

}