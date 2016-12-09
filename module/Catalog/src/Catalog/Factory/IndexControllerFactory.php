<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Factory;

use Catalog\Controller\IndexController;
use Catalog\Service\CategoryServiceInterface;
use Catalog\Service\PdfService;
use Catalog\Service\ProductService;
use Catalog\Service\ProductServiceInterface;
use Interop\Container\ContainerInterface;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\RendererInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $categoryService = $container->get(CategoryServiceInterface::class);
        $productService = $container->get(ProductServiceInterface::class);
        $pdfService = $container->get(PdfService::class);
        $renderer = $container->get(RendererInterface::class);

        return new IndexController(
            $categoryService,
            $productService,
            $pdfService,
            $renderer
        );
    }


    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //return $this($serviceLocator, IndexController::class);
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $categoryService        = $realServiceLocator->get('Catalog\Service\CategoryServiceInterface');
        $productService = $realServiceLocator->get('Catalog\Service\ProductServiceInterface');
        $renderer = $realServiceLocator->get('Zend\View\Renderer\RendererInterface');
        $pdfService       = $realServiceLocator->get('Catalog\Service\PdfService');

        return new IndexController(
            $categoryService,
            $productService,
            $pdfService,
            $renderer
        );
    }
}