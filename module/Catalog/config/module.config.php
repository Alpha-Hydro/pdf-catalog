<?php
namespace Catalog;

use Catalog\Factory\IndexControllerFactory;
use Catalog\Factory\ProductsControllerFactory;

return array(
    'controllers' => array(
        'factories' => array(
            'Catalog\Controller\Index' => IndexControllerFactory::class,
            'Catalog\Controller\Products' => ProductsControllerFactory::class,
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
                    'product' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/product',
                            'defaults' => array(
                                'controller' => 'Catalog\Controller\Products',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'view' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route'    => '/:id',
                                    'defaults' => array(
                                        'action' => 'view'
                                    ),
                                    'constraints' => array(
                                        'id' => '[0-9]\d*'
                                    )
                                )
                            ),
                        ),
                    ),
                    'pdf' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/pdf[/:id]',
                            'defaults' => array(
                                'action' => 'pdf'
                            ),
                            'constraints' => array(
                                'id' => '[0-9]\d*'
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
            'ViewFeedStrategy'
        ),
    ),
);