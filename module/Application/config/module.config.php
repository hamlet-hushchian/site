<?php

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'result' =>[
                'type' => Segment::class,
                'options' => [
                    'route'     => '/:d_type{-}-:p_type{-}-:city',
                    'defaults'  => [
                        'controller' => Controller\ResultController::class,
                        'action' => 'index',
                    ],
                    'constraints' => [
                        'd_type' => '(prodazha|arenda)',
                        'p_type' => '(kvartir|komnat|domov)',
                        'city' => '(kiev|kievs)'
                    ],
                ],
            ],
            'listing' => [
                'type' => Segment::class,
                'options' => [
                    'route'     => '/listing/:id',
                    'defaults'  => [
                        'controller' => Controller\ListingController::class,
                        'action'    => 'index',
                    ],
                    'constraints' => [
                        'id' => '\d+',
                    ]
                ]
            ],
            'newhouse' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/newhouse',
                    'defaults' => [
                        'controller' => Controller\NewhouseController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            'news' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/news',
                    'defaults' => [
                        'controller' => Controller\NewsController::class,
                        'action' => 'index'
                    ]
                ]
            ],
            'contacts' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/contacts',
                    'defaults' => [
                        'controller' => Controller\ContactsController::class,
                        'action' => 'index'
                    ]
                ]
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ResultController::class => Controller\Factory\ResultControllerFactory::class,
            Controller\ListingController::class => Controller\Factory\ListingControllerFactory::class,
            Controller\NewhouseController::class => InvokableFactory::class,
            Controller\NewsController::class => InvokableFactory::class,
            Controller\ContactsController::class => Controller\Factory\ContactsControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\SearchManager::class => InvokableFactory::class,
            // ...
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\SearchPanel::class => View\Helper\Factory\SearchPanelFactory::class,
            View\Helper\Bredcrumbs::class => View\Helper\Factory\BredcrumbsFactory::class,
            View\Helper\H1::class => InvokableFactory::class,
            View\Helper\CountResults::class => InvokableFactory::class,
        ],
        'aliases' => [
            'searchPanel' => View\Helper\SearchPanel::class,
            'bredcrumbs' => View\Helper\Bredcrumbs::class,
            'h1' => View\Helper\H1::class,
            'countResults' => View\Helper\CountResults::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/searchResult'           => __DIR__ . '/../view/layout/search-result.phtml',
            'layout/listing'           => __DIR__ . '/../view/layout/listing.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
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
    'access_filter' => [
        'options' => [
            'mode' => 'permissive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                // Позволяем всем обращаться к действиям "index".
                ['actions' => ['index',], 'allow' => '*'],
            ],
            Controller\ResultController::class => [
                // Позволяем всем обращаться к действиям "index".
                ['actions' => ['index',], 'allow' => '*'],
            ],
            Controller\ListingController::class => [
                // Позволяем всем обращаться к действиям "index".
                ['actions' => ['index',], 'allow' => '*'],
            ],
            Controller\NewhouseController::class => [
                // Позволяем всем обращаться к действиям "index".
                ['actions' => ['index',], 'allow' => '*'],
            ],
            Controller\NewsController::class => [
                // Позволяем всем обращаться к действиям "index".
                ['actions' => ['index',], 'allow' => '*'],
            ],
            Controller\ContactsController::class => [
                // Позволяем всем обращаться к действиям "index".
                ['actions' => ['index',], 'allow' => '*'],
            ],
        ]
    ],

];
