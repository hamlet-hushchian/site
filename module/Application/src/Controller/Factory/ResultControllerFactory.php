<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\ResultController;

Class ResultControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $opt = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $listingManager = $container->get(\Admin\Service\ListingManager::class);

        return new ResultController($entityManager, $listingManager);
    }
}