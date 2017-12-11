<?php
namespace Feed;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'feed' => [
                'type'    => 'Literal',
                'options' => [
                    // Change this to something specific to your module
                    'route'    => '/generate-feed',
                    'defaults' => [
                        'controller'    => Controller\FeedController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\FeedController::class => Controller\Factory\FeedControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\XmlManager::class => Service\Factory\XmlManagerFactory::class,
            // ...
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'template_map' => [
            'layout/red-head-footer'           => __DIR__ . '/../../Application/view/layout/listing.phtml',
        ],
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'permissive'
        ],
        'controllers' => [
            Controller\FeedController::class => [
                // Позволяем всем обращаться к действиям "index".
                ['actions' => ['index',], 'allow' => '*'],
            ],
        ]
    ],
    'xml_file' => [
        'lun' => __DIR__ . '/../../../public/assets/feed/lun.xml',
    ]
];
