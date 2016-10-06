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
        // Open configuration for all possible routes
        'routes' => array(
            // Define a new route called "post"
            'catalog' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'literal',
                // Configure the route itself
                'options' => array(
                    // Listen to "/blog" as uri
                    'route'    => '/catalog',
                    // Define default controller and action to be called when this route is matched
                    'defaults' => array(
                        'controller' => 'Catalog\Controller\Index',
                        'action'     => 'index',
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