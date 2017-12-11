<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\ContactsController;

Class ContactsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,$requestedName, array $opt = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new ContactsController($entityManager);
    }
}