<?php
namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\ListingController;

Class ListingControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,$requestedName, array $opt = null)
    {
        $sessionContainer = $container->get('AddListing');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $listingManager = $container->get(\Admin\Service\ListingManager::class);

        return new ListingController($sessionContainer,$entityManager,$listingManager);
    }
}