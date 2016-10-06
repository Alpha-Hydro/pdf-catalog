<?php
namespace Catalog;

use Catalog\Service\CategoryService;
use Catalog\Service\CategoryServiceInterface;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return [
            'abstract_factories' => [],
            'aliases' => [],
            'factories' => [],
            'invokables' => [
                CategoryServiceInterface::class => CategoryService::class,
            ],
            'services' => [],
            'shared' => [],
        ];
    }
}
