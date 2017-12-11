<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\ListingController;

Class ListingControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,$requestedName, array $opt = null)
    {
        $listingManager = $container->get(\Admin\Service\ListingManager::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new ListingController($listingManager,$entityManager);
    }
}