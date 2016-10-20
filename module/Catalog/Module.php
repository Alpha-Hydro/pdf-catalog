<?php
namespace Catalog;

use Catalog\Factory\CategoryServiceFactory;
use Catalog\Factory\PdfServiceFactory;
use Catalog\Factory\ZendDbSqlMapperFactory;
use Catalog\Mapper\CategoryMapperInterface;
use Catalog\Service\CategoryServiceInterface;

use Catalog\Service\PdfService;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterServiceFactory;

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
            'factories' => [
                CategoryMapperInterface::class => ZendDbSqlMapperFactory::class,
                CategoryServiceInterface::class => CategoryServiceFactory::class,
                PdfService::class => PdfServiceFactory::class,
                Adapter::class => AdapterServiceFactory::class,
            ],
            'invokables' => [],
            'services' => [],
            'shared' => [],
        ];
    }
}
