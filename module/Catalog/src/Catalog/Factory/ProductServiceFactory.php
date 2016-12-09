<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Factory;

use Catalog\Mapper\ModificationMapperInterface;
use Catalog\Mapper\ModificationPropertyMapperInterface;
use Catalog\Mapper\ModificationPropertyValueMapperInterface;
use Catalog\Mapper\ProductModificationParamValuesMapperInterface;
use Catalog\Mapper\ProductParamsMapperInterface;
use Catalog\Mapper\ProductMapperInterface;
use Catalog\Service\ProductService;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ProductService(
            $container->get(ProductMapperInterface::class),
            $container->get(ProductParamsMapperInterface::class),
            $container->get(ModificationMapperInterface::class),
            $container->get(ModificationPropertyMapperInterface::class),
            $container->get(ModificationPropertyValueMapperInterface::class),
            $container->get(ProductModificationParamValuesMapperInterface::class)
        );
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, ProductService::class);
    }
}