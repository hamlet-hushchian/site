<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой зарегистрированного пользователя.
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User
{

    // Константы статуса пользователя.
    const STATUS_ACTIVE       = 1; // Активный пользователь.
    const STATUS_RETIRED      = 2; // Неактивный пользователь.

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="login")
     */
    protected $login;

    /**
     * @ORM\Column(name="password")
     */
    protected $password;

    /**
     * @ORM\Column(name="phone")
     */
    protected $phone;

    /**
     * Возвращает ID пользователя.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Задает ID пользователя.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает Логин пользователя.
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Задает Логин пользователя.
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * Возвращает статус.
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Возвращает возможные статусы в виде массива.
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }

    /**
     * Возвращает статус пользователя в виде строки.
     * @return string
     */
    public function getStatusAsString()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

        return 'Unknown';
    }

    /**
     * Устанавливает статус.
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Возвращает пароль.
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Задает пароль.
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Возвращает Телефон пользователя.
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Задает Телефон пользователя.
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
}