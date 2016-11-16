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
use Catalog\Mapper\ProductParamsMapperInterface;
use Catalog\Mapper\ProductMapperInterface;
use Catalog\Service\ProductService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ProductService(
            $serviceLocator->get(ProductMapperInterface::class),
            $serviceLocator->get(ProductParamsMapperInterface::class),
            $serviceLocator->get(ModificationMapperInterface::class),
            $serviceLocator->get(ModificationPropertyMapperInterface::class)
        );
    }
}