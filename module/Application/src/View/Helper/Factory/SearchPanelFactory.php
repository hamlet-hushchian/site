<?php
namespace Application\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\View\Helper\SearchPanel;

Class SearchPanelFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,$requestedName, array $opt = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new SearchPanel($entityManager);
    }
}