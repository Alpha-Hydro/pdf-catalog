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
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $categoryService        = $realServiceLocator->get('Catalog\Service\CategoryServiceInterface');
        $renderer = $realServiceLocator->get('Zend\View\Renderer\RendererInterface');
        $dompdf        = $realServiceLocator->get('dompdf');
        //$tcpdf       = $realServiceLocator->get('TCPDF');
        $tcpdf       = $realServiceLocator->get('Catalog\Service\PdfServiceInterface');

        return new IndexController(
            $categoryService,
            $dompdf,
            $tcpdf,
            $renderer
        );
    }
}