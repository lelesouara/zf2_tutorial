<?php

namespace Loja;

return array(
    'controllers' => array(
        'invokables' => array(
            'Loja\Controller\Loja' => 'Loja\Controller\LojaController'
        )
    ),
    'router' => array(
        'routes' => array(
            'loja' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/loja[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Loja\Controller\Loja',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'loja' => __DIR__ . '/../view',
        ),
    ),
    //Doctrine Configuration
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);