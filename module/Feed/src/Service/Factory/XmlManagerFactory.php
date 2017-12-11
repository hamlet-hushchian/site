<?php
namespace Feed\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Feed\Service\XmlManager;

Class XmlManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,$requestedName, array $opt = null)
    {
        $config = $container->get('Config');
        if (!isset($config['xml_file']))
            $config = [];

        return new XmlManager($config);
    }
}