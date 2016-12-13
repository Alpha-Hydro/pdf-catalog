<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Factory;


use Catalog\Mapper\CategoryMapperInterface;
use Catalog\Mapper\ModificationMapperInterface;
use Catalog\Mapper\ModificationPropertyMapperInterface;
use Catalog\Mapper\ModificationPropertyValueMapperInterface;
use Catalog\Mapper\ProductMapperInterface;
use Catalog\Mapper\ProductModificationParamValuesMapperInterface;
use Catalog\Mapper\ProductParamsMapperInterface;
use Catalog\Service\BaseService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BaseService(
            $container->get(CategoryMapperInterface::class),
            $container->get(ProductMapperInterface::class),
            $container->get(ProductParamsMapperInterface::class),
            $container->get(ModificationMapperInterface::class),
            $container->get(ModificationPropertyMapperInterface::class),
            $container->get(ModificationPropertyValueMapperInterface::class),
            $container->get(ProductModificationParamValuesMapperInterface::class),
            $container->get('cache')
        );
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, BaseService::class);
    }

}