<?php

namespace Catalog\Controller;

use Catalog\Service\CategoryServiceInterface;
use Catalog\Service\PdfService;
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
     * @var PdfService
     */
    protected $pdfService = null;

    /**
     * @var RendererInterface
     */
    protected $renderer = null;

    public function __construct(
        CategoryServiceInterface $categoryService,
        PdfService $pdfService,
        $renderer
    )
    {
        $this->categoryService = $categoryService;
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

        $view = new ViewModel();
        $view->setTemplate('partial/pdf/introduction');
        $html = $this->renderer->render($view);
        $pdf->introduction($html);

        $id = '0';
        $view = new ViewModel([
            'categories' => $this->categoryService->findTreeByParentId($id)
        ]);

        $view->setTemplate('partial/pdf/table-of-content');
        $html = $this->renderer->render($view);
        $pdf->tableOfContent($html);

        $pdf->Output('catalog.pdf', 'I');
    }

}

