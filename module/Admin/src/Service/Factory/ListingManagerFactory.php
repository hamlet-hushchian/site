<?php
namespace Admin\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Service\ListingManager;

Class ListingManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,$requestedName, array $opt = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new ListingManager($entityManager);
    }
}