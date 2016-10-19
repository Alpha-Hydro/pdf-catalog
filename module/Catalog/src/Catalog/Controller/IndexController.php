<?php

namespace Catalog\Controller;

use Catalog\Service\CategoryServiceInterface;
use TCPDF;
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
     * @var \Dompdf\Dompdf
     */
    protected $dompdf = null;

    /**
     * @var TCPDF
     */
    protected $tcpdf = null;

    /**
     * @var RendererInterface
     */
    protected $renderer = null;

    public function __construct(
        \Catalog\Service\CategoryServiceInterface $categoryService,
        $dompdf,
        $tcpdf,
        $renderer
    )
    {
        $this->categoryService = $categoryService;
        $this->dompdf = $dompdf;
        $this->tcpdf = $tcpdf;
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
        $id = '0';
        $view = new ViewModel([
            'categories' => $this->categoryService->findTreeByParentId($id)
        ]);

        $renderer = $this->renderer;
        $view->setTemplate('catalog/index/pdf');
        $html = $renderer->render($view);

        \Zend\Debug\Debug::dump($html);die();
    }

    public function tcpdfAction()
    {

        $id = '0';
        $view = new ViewModel([
            'categories' => $this->categoryService->findTreeByParentId($id)
        ]);

        $renderer = $this->renderer;
        $view->setTemplate('catalog/index/pdf');
        $html = $renderer->render($view);

        $pdf = $this->tcpdf;

        $pdf->SetCreator('Alpha-Hydro');
        $pdf->SetAuthor('Alpha-Hydro');
        $pdf->SetTitle('Alpha-Hydro. Каталог товаров. Содержание');
        $pdf->SetSubject('Alpha-Hydro');
        $pdf->SetKeywords('Alpha-Hydro, PDF, каталог, гидравлика');

        // set default header data
        $pdf->SetHeaderData('', 0, 'Содержание', '');

        // set header and footer fonts
        $pdf->setHeaderFont(array('arialnarrow', '', 12));
        $pdf->setFooterFont(array('arialnarrow', '', 12));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont('arialnarrow');

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 20);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        $pdf->SetFont('arialnarrow', '', 12, '', false);

        $pdf->AddPage();

        $pdf->writeHTML($html);

        //$pdf->lastPage();

        $pdf->Output();
    }

    public function dompdfAction()
    {
        $dompdf = $this->dompdf;
        $dompdf->loadHtml('<strong>Привет мир</strong>');
        $dompdf->render();
        $dompdf->stream(null, ['Attachment' => 0]);
    }


}

