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
                    'detail' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/:id',
                            'defaults' => array(
                                'action' => 'detail'
                            ),
                            'constraints' => array(
                                'id' => '[1-9]\d*'
                            )
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);