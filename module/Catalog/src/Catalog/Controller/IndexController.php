<?php

namespace Catalog\Controller;

use Catalog\Model\ModificationInterface;
use Catalog\Service\CategoryServiceInterface;
use Catalog\Service\PdfService;
use Catalog\Service\ProductServiceInterface;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;

class IndexController extends AbstractActionController
{

    /**
     * @var CategoryServiceInterface
     */
    protected $categoryService = null;

    /**
     * @var ProductServiceInterface
     */
    protected $productService = null;

    /**
     * @var PdfService
     */
    protected $pdfService = null;

    /**
     * @var RendererInterface
     */
    protected $renderer = null;

    public function __construct(
        \Catalog\Service\CategoryServiceInterface $categoryService,
        \Catalog\Service\ProductServiceInterface $productService,
        \Catalog\Service\PdfService $pdfService,
        $renderer
    )
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->pdfService = $pdfService;
        $this->renderer = $renderer;
    }

    public function indexAction()
    {
        $id = '0';
        return new ViewModel([
            'categories' => $this->categoryService->fetchSubCategories($id)
        ]);
    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');

        return new ViewModel([
            'category' => $this->categoryService->find($id)
        ]);
    }

    public function listAction()
    {
        $id = $this->params()->fromRoute('id');

        return new ViewModel([
            'category' => ($id != 0)?$this->categoryService->find($id):null,
            'subCategories' => $this->categoryService->fetchSubCategories($id)
        ]);
    }

    public function treeAction()
    {
        $id = ($this->params()->fromRoute('id'))?$this->params()->fromRoute('id'):0;

        //Debug::dump($this->categoryService->fetchAllProductsByCategory($id));

        return new JsonModel(
            //$this->categoryService->findTreeByParentId($id)
            $this->categoryService->fetchAllProductsByCategory($id)
        );
    }

    public function pdfAction()
    {
        $id = ($this->params()->fromRoute('id'))?$this->params()->fromRoute('id'):0;

        $pdf = $this->pdfService;
        $pdf->defaultSettingsPage();
        $pdf->setProductProperty($this->productService->fetchAllProductParams());
        $pdf->setProductTableModification($this->productService->fetchAllProductModificationParamValues());


        //Введение
        $view = new ViewModel();
        $view->setTemplate('partial/pdf/introduction');
        $html = $this->renderer->render($view);
        $pdf->introduction($html);

        $pdf->tableOfContent($this->categoryService->findTreeByParentId($id));

        /*//Products
        $products = $this->productService->fetchAll();

        foreach ($products as $product){
            $view = new ViewModel([
                'product' => $product,
                'productParams' => $this->productService->fetchParamsByProduct($product->getId())
            ]);

            $view->setTemplate('partial/pdf/product');
            $html = $this->renderer->render($view);
            $pdf->viewProduct($html);
        }*/



        $pdf->Output('catalog.pdf', 'I');
    }
}

