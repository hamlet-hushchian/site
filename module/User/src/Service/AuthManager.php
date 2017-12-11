<?php
namespace User\Service;

use Zend\Authentication\Result;

class AuthManager
{
    /**
     * Authentication service.
     * @var \Zend\Authentication\AuthenticationService
     */
    private $authService;

    /**
     * Session manager.
     * @var Zend\Session\SessionManager
     */
    private $sessionManager;

    /**
     * Содержимое 'access_filter'.
     * @var array
     */
    private $config;

    public function __construct($authService, $sessionManager, $config)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->config = $config;
    }


    public function login($login, $password, $rememberMe)
    {
        if ($this->authService->getIdentity()!=null) {
            throw new \Exception('Already logged in');
        }

        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setLogin($login);
        $authAdapter->setPassword($password);
        $result = $this->authService->authenticate();

        if ($result->getCode()==Result::SUCCESS && $rememberMe) {
            // Session cookie will expire in 1 month (30 days).
            $this->sessionManager->rememberMe(60*60*24*30);
        }

        return $result;
    }

    public function logout()
    {

        if ($this->authService->getIdentity()==null) {
            throw new \Exception('The user is not logged in');
        }


        $this->authService->clearIdentity();
    }


    public function filterAccess($controllerName, $actionName)
    {
        $mode = isset($this->config['options']['mode'])?$this->config['options']['mode']:'restrictive';
        if ($mode!='restrictive' && $mode!='permissive')
            throw new \Exception('Invalid access filter mode (expected either restrictive or permissive mode');

        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];
            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                if (is_array($actionList) && in_array($actionName, $actionList) ||
                    $actionList=='*') {
                    if ($allow=='*')
                        return true; // Все могут просматривать страницу
                    else if ($allow=='@' && $this->authService->hasIdentity()) {
                        return true; // Только авторизированные пользователи могут просматривать страницу
                    } else {
                        return false;
                    }
                }
            }
        }


        if ($mode=='restrictive' && !$this->authService->hasIdentity())
            return false;

        // Разрешаем доступ к странице
        return true;
    }
}