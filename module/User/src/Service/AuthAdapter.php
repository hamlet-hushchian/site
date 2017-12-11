<?php
namespace User\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use User\Entity\User;

/**
 * Это адаптер, используемый для аутентификации пользователя.
 */
class AuthAdapter implements AdapterInterface
{

    /**
     * Логин пользователя.
     * @var string
     */
    private $login;

    /**
     * Пароль.
     * @var string
     */
    private $password;

    /**
     * Менеджер сущностей.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Конструктор.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Задает эл. адрес пользователя.
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * Устанавливает пароль.
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
    }

    /**
     * Выполняет попытку аутентификации.
     */
    public function authenticate()
    {
        // Проверяем, есть ли в базе данных пользователь с таким адресом.
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByLogin($this->login);

        // Если такого пользователя нет, возвращаем статус 'Identity Not Found'.
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']);
        }

        // Если пользователь с таким адресом существует, проверим, активен ли он.
        if ($user->getStatus()==User::STATUS_RETIRED) {
            return new Result(
                Result::FAILURE,
                null,
                ['User is retired.']);
        }

        // Вычисляем хеш
//        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($this->password == $user->getPassword()) {
            return new Result(
                Result::SUCCESS,
                $this->login,
                ['Authenticated successfully.']);
        }

        // Если пароль не прошел проверку, возвращаем статус ошибки 'Invalid Credential'.
        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }
}