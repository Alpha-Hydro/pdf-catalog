<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Factory;

use Catalog\Mapper\ZendDbSqlMapper;
use Catalog\Model\Category;
use Catalog\Model\Product;
use Catalog\Model\ProductParams;
use Catalog\Model\Modification;
use Catalog\Model\ModificationProperty;
use Catalog\Model\ModificationPropertyValue;
use Catalog\Model\ProductModificationParamValues;

use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ZendDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ZendDbSqlMapper(
            $serviceLocator->get(Adapter::class),
            $serviceLocator->get('cache'),
            new ClassMethods(),// new ClassMethods(false),
            new Category(),
            new Product(),
            new ProductParams(),
            new Modification(),
            new ModificationProperty(),
            new ModificationPropertyValue(),
            new ProductModificationParamValues()
        );
    }
}