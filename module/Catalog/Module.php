<?php
namespace Catalog;

use Catalog\Factory\CategoryServiceFactory;
use Catalog\Factory\PdfServiceFactory;
use Catalog\Factory\ProductServiceFactory;
use Catalog\Factory\ZendDbSqlMapperFactory;
use Catalog\Mapper\CategoryMapperInterface;
use Catalog\Mapper\ProductMapperInterface;
use Catalog\Service\CategoryServiceInterface;

use Catalog\Service\PdfService;
use Catalog\Service\ProductServiceInterface;
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
                ProductMapperInterface::class => ZendDbSqlMapperFactory::class,
                CategoryServiceInterface::class => CategoryServiceFactory::class,
                ProductServiceInterface::class => ProductServiceFactory::class,
                PdfService::class => PdfServiceFactory::class,
                Adapter::class => AdapterServiceFactory::class,
            ],
            'invokables' => [],
            'services' => [],
            'shared' => [],
        ];
    }
}
