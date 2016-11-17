<?php

namespace Catalog\Controller;

use Catalog\Model\ModificationInterface;
use Catalog\Service\CategoryServiceInterface;
use Catalog\Service\PdfService;
use Catalog\Service\ProductServiceInterface;
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
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
            'categories' => $this->categoryService->findCategoriesByParentId($id)
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
            'subCategories' => $this->categoryService->findCategoriesByParentId($id)
        ]);
    }

    public function treeAction()
    {
        $id = ($this->params()->fromRoute('id'))?$this->params()->fromRoute('id'):0;

        /*return new JsonModel(
            $this->categoryService->findTreeByParentId($id)
        );*/

        return new ViewModel([
            'category' => ($id != 0)?$this->categoryService->find($id):null,
            'subCategories' => $this->categoryService->findTreeByParentId($id)
        ]);
    }

    public function pdfAction()
    {
        $pdf = $this->pdfService;
        $pdf->defaultSettingsPage();


        /*//Введение
        $view = new ViewModel();
        $view->setTemplate('partial/pdf/introduction');
        $html = $this->renderer->render($view);
        $pdf->introduction($html);

        //Содержание
        $id = '0';
        $view = new ViewModel([
            'categories' => $this->categoryService->findTreeByParentId($id)
        ]);
        $view->setTemplate('partial/pdf/table-of-content');
        $html = $this->renderer->render($view);
        $pdf->tableOfContent($html);*/

        //Product
        $id = '27816';
        $view = new ViewModel([
            'product' => $this->productService->find($id),
            'productParams' => $this->productService->fetchParamsByProduct($id)
        ]);

        $view->setTemplate('partial/pdf/product');
        $html = $this->renderer->render($view);
        $pdf->viewProduct($html);

        $pdf->Output('catalog.pdf', 'I');
    }

    public function productAction()
    {
        $id = $this->params()->fromRoute('id');
        if($id){
            //Debug::dump($this->productService->find($id));
            //Debug::dump($this->productService->fetchParamsByProduct($id));
        }

        $modifications = $this->productService->fetchModificationsByProduct($id);
        //Debug::dump($this->modificationTableValues($modifications));

        return new ViewModel([
            'product' => $this->productService->find($id),
            'productParams' => $this->productService->fetchParamsByProduct($id),
            'modifications' => $this->productService->fetchModificationsByProduct($id),
            'modificationsProperty' => $this->productService->fetchModificationPropertyByProduct($id),
            'modificationsTable' => $this->modificationTableValues($modifications)
        ]);
    }

    /**
     * @param $modifications array | ModificationInterface[]
     * @return array
     */
    public function modificationTableValues($modifications)
    {
        $modificationsTableValues = array();
        $modificationsArray = $modifications->toArray();
        if(!empty($modificationsArray))
            foreach ($modificationsArray as $modification){
                $values = array();
                $values[] = $modification["sku"];
                $modificationPropertyValues = $this->productService->fetchModificationPropertyValues($modification['id']);
                foreach ($modificationPropertyValues->toArray() as $modificationPropertyValue){
                    $values[] = $modificationPropertyValue['value'];
                }

                $modificationsTableValues[] = $values;
            }


        return $modificationsTableValues;
    }

}

