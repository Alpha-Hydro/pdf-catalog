<?php

namespace Catalog\Controller;

use Catalog\Service\CategoryServiceInterface;
use TCPDF;
use TCPDF_FONTS;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
        // instantiate and use the dompdf class
        /*$dompdf = new Dompdf();
        $dompdf->loadHtml('Привет мир');

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream(null, ['Attachment' => 0]);*/


        /*$pdf = new Module();
        $pdf = $pdf->MyPdf();

        $pdf->setHeaderData($ln = 0,$lw = 0,$ht = 0,$hs = 0,$tc = array(255,255,255),$lc = array(255,255,255));
        $pdf->setFooterData($tc = array(255,255,255),$lc = array(255,255,255));
        $pdf->setPageOrientation($orientation='P', $autopagebreak='L', $bottommargin=-200);

        $pdf->AddPage();

        $pdf->Write(0, 'Catalog');

        $pdf->Output();*/
    }

    public function tcpdfAction()
    {
        TCPDF_FONTS::addTTFfont(__DIR__.'/../../../../../data/fonts/ArialNarrow.ttf', 'TrueTypeUnicode');
        TCPDF_FONTS::addTTFfont(__DIR__.'/../../../../../data/fonts/ArialNarrow-Bold.ttf', 'TrueTypeUnicode');
        TCPDF_FONTS::addTTFfont(__DIR__.'/../../../../../data/fonts/ArialNarrow-BoldItalic.ttf', 'TrueTypeUnicode');
        TCPDF_FONTS::addTTFfont(__DIR__.'/../../../../../data/fonts/ArialNarrow-Italic.ttf', 'TrueTypeUnicode');


        /*$view = new ViewModel();

        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\RendererInterface');
        $view->setTemplate('partial/test');
        $html = $renderer->render($view);*/

        $html = '<h1>Привет Мир</h1>';

        $pdf = new TCPDF();

        $pdf->SetFont('arialnarrow', '', 14, '', false);

        $pdf->AddPage();
        //$pdf->Write(20, 'Привет мир');
        $pdf->writeHTML($html);
        $pdf->Output();
    }

    public function dompdfAction()
    {
        return new ViewModel();
    }


}

