<?php
namespace Catalog;

use Catalog\Factory\IndexControllerFactory;

return array(
    'controllers' => array(
        'factories' => array(
            'Catalog\Controller\Index' => IndexControllerFactory::class,
        )
    ),
    'router' => array(
        'routes' => array(
            'catalog' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/catalog',
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Index',
                        'action'     => 'index',
                    )
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'list' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/:id',
                            'defaults' => array(
                                'action' => 'list'
                            ),
                            'constraints' => array(
                                'id' => '[0-9]\d*'
                            )
                        )
                    ),
                    'tree' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/tree[/:id]',
                            'defaults' => array(
                                'action' => 'tree'
                            ),
                            'constraints' => array(
                                'id' => '[0-9]\d*'
                            )
                        )
                    ),
                    'detail' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/detail/:id',
                            'defaults' => array(
                                'action' => 'detail'
                            ),
                            'constraints' => array(
                                'id' => '[0-9]\d*'
                            )
                        )
                    ),
                    'pdf' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/pdf',
                            'defaults' => array(
                                'action' => 'pdf'
                            )
                        )
                    ),
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);