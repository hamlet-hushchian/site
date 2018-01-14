<?php
namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'listings' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/listings[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\ListingController::class,
                        'action' => 'index',
                    ],
                    'constraints' => [
                        'id' => '\d+',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ListingController::class => Controller\Factory\ListingControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\ListingManager::class => Service\Factory\ListingManagerFactory::class,
            // ...
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => __DIR__ . '/../view/error/404',
        'exception_template'       => __DIR__ . '/../../Application/view/error/index',
        'template_map' => [
//            'layout/admin'           => __DIR__ . '/../view/layout/admin',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\ListingController::class => [
                // Позволяем всем обращаться к действиям "index".
                ['actions' => ['index','copy'], 'allow' => '*'],
                // Позволяем вошедшим на сайт пользователям обращаться к действию "settings".
//                ['actions' => ['settings'], 'allow' => '@']
            ],
        ]
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class
        ]
    ],
    'session_containers' => [
        'AddListing',
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'exchange_rate' => [
        'usd' => '26.3',
        'eur' => '31.35',
    ]
];