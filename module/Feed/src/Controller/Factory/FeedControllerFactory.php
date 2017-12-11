<?php
namespace Feed\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Feed\Controller\FeedController;

Class FeedControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,$requestedName, array $opt = null)
    {
        $em = $container->get('doctrine.entitymanager.orm_default');
        $xm = $container->get(\Feed\Service\XmlManager::class);

        return new FeedController($em,$xm);
    }
}