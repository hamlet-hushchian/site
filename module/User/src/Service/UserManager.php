<?php
namespace User\Service;

use User\Entity\User;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

/**
 * Этот сервис отвечает за добавлениие/редактирование пользователя
 * и изменение пароля
 */
class UserManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Этот метод добавляет нового пользователя.
     */
    public function addUser($data)
    {

        if($this->checkUserExists($data['email'])) {
            throw new \Exception("User with email address " . $data['$email'] . " already exists");
        }


        $user = new User();
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);

        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);
        $user->setPassword($passwordHash);

        $user->setStatus($data['status']);

        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);


        $this->entityManager->persist($user);


        $this->entityManager->flush();

        return $user;
    }

    /**
     * Этот метод обновляет данные существующего пользователя.
     */
    public function updateUser($user, $data)
    {

        if($user->getLogin()!=$data['login'] && $this->checkUserExists($data['login'])) {
            throw new \Exception("Another user with login " . $data['login'] . " already exists");
        }

        $user->setLogin($data['login']);

        $user->setStatus($data['status']);


        $this->entityManager->flush();
        return true;
    }

    /**
     * Этот метод проверяет существует ли хоть один пользователь, и если нет, создает
     * 'Admin' пользователя с email 'admin@example.com' и паролем 'Secur1ty'.
     */
    public function createAdminUserIfNotExists()
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([]);
        if ($user==null) {
            $user = new User();
            $user->setEmail('admin@example.com');
            $user->setFullName('Admin');
            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create('Secur1ty');
            $user->setPassword($passwordHash);
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setDateCreated(date('Y-m-d H:i:s'));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    /**
     * Этот метод проверяет наличие пользователя с данным email адресом в БД
     */
    public function checkUserExists($login) {

        $user = $this->entityManager->getRepository(User::class)
            ->findOneByLogin($login);

        return $user !== null;
    }

    /**
     * Проверяет правильность даного пароля.
     */
    public function validatePassword($user, $password)
    {
//        $bcrypt = new Bcrypt();
//        $passwordHash = $user->getPassword();
        $userPassword = $user->getPassword();

        if ($password == $userPassword) {
            return true;
        }

        return false;
    }

    /**
     * Этот метод генерирует токен для востановления пароля
     */
    public function generatePasswordResetToken($user)
    {
        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);
        $user->setPasswordResetToken($token);

        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate);

        $this->entityManager->flush();

        $subject = 'Востановление пароля';

        $httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
        $passwordResetUrl = 'http://' . $httpHost . '/set-password?token=' . $token;

        $body = 'Пожалуйста перейдите по следуйщей ссылке для востановления пароля:\n';
        $body .= "$passwordResetUrl\n";
        $body .= "Если вы не запрашивали востановление пароля, просто проигнорируйте это сообщение\n";

        // Отправляем письмо пользователю
        mail($user->getEmail(), $subject, $body);
    }

    /**
     * Проверяет валидность даного токена.
     */
    public function validatePasswordResetToken($passwordResetToken)
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneByPasswordResetToken($passwordResetToken);

        if($user==null) {
            return false;
        }

        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);

        $currentDate = strtotime('now');

        if ($currentDate - $tokenCreationDate > 24*60*60) {
            return false;
        }

        return true;
    }

    /**
     * Этот метод устанавливает новый пароль.
     */
    public function setNewPasswordByToken($passwordResetToken, $newPassword)
    {
        if (!$this->validatePasswordResetToken($passwordResetToken)) {
            return false;
        }

        $user = $this->entityManager->getRepository(User::class)
            ->findOneByPasswordResetToken($passwordResetToken);

        if ($user==null) {
            return false;
        }

        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);

        // Remove password reset token
        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenCreationDate(null);

        $this->entityManager->flush();

        return true;
    }

    /**
     * Этот метод изменяет пароль пользователя.
     */
    public function changePassword($user, $data)
    {
        $oldPassword = $data['old_password'];


        if (!$this->validatePassword($user, $oldPassword)) {
            return false;
        }

        $newPassword = $data['new_password'];


        if (strlen($newPassword)<6 || strlen($newPassword)>64) {
            return false;
        }


        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);

        
        $this->entityManager->flush();
        return true;
    }
}