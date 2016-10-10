<?php

namespace Catalog\Controller;

use Catalog\Service\CategoryServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DOMPDFModule\View\Model\PdfModel;
use QuTcPdf\Module;

class IndexController extends AbstractActionController
{

    /**
     * @var CategoryServiceInterface
     */
    protected $categoryService = null;

    public function __construct(\Catalog\Service\CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
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
        $pdf = new PdfModel();
        //$pdf->setOption('filename', 'monthly-report'); // Triggers PDF download, automatically appends ".pdf"
        $pdf->setOption('paperSize', 'a4'); // Defaults to "8x11"
        //$pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"
        $pdf->setOption('defaultFont', 'DejaVu');

        //\Zend\Debug\Debug::dump($pdf->getOptions());

        $id = 0;
        // To set view variables
        $pdf->setVariables(array(
            'categories' => $this->categoryService->findTreeByParentId($id)
        ));

        return $pdf;

        /*$pdf = new Module();
        $pdf = $pdf->MyPdf();

        $pdf->setHeaderData($ln = 0,$lw = 0,$ht = 0,$hs = 0,$tc = array(255,255,255),$lc = array(255,255,255));
        $pdf->setFooterData($tc = array(255,255,255),$lc = array(255,255,255));
        $pdf->setPageOrientation($orientation='P', $autopagebreak='L', $bottommargin=-200);

        $pdf->AddPage();

        $pdf->Write(0, 'Catalog');

        $pdf->Output();*/
    }


}

