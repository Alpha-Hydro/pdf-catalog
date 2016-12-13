<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Factory;

use Catalog\Controller\ProductsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ProductsControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $productService = $realServiceLocator->get('Catalog\Service\ProductServiceInterface');
        $baseService = $realServiceLocator->get('Catalog\Service\BaseServiceInterface');

        return new ProductsController(
            $productService,
            $baseService
        );
    }

}