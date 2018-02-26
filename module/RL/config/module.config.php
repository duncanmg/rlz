<?php

namespace RL;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
#    'controllers' => [
#        'factories' => [
#            Controller\QuestionController::class => InvokableFactory::class,
#        ],
#    ],

    'router' => [
        'routes' => [
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\QuestionAdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'question' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/question[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\QuestionController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'rl' => __DIR__ . '/../view',
        ],
    ],
];
