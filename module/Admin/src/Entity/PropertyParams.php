<?php

namespace Admin\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Этот класс представляет собой параметры.
 * @ORM\Entity()
 * @ORM\Table(name="property_params")
 */
class PropertyParams
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="param_key")
     */
    protected $paramKey;

    /**
     * @ORM\Column(name="param_name")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="\Admin\Entity\PropertyParamsValue", mappedBy="propertyParams")
     */
    protected $values;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->values = new ArrayCollection();
    }

    public function getValues()
    {
        return $this->values;
    }

    public function setValue($value)
    {
        $this->values[] = $value;
    }

    /**
     * Возвращает ID параметрыа.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Задает ID параметрыа.
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает param_key параметрыа.
     * @return string
     */
    public function getParamKey()
    {
        return $this->paramKey;
    }

    /**
     * Задает param_key параметрыа.
     * @param $param_key
     */
    public function setParamKey($param_key)
    {
        $this->paramKey = $param_key;
    }

    /**
     * Возвращает param_name параметрыа.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Задает param_name параметрыа.
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}