<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;

use TCPDF;
use TCPDF_IMAGES;


class PdfService extends TCPDF implements PdfServiceInterface
{
    protected $_widthWorkspacePage;

    protected $_last_page_flag = true;

    public function __construct()
    {
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->setWidthWorkspacePage($this->getPageWidth()-PDF_MARGIN_LEFT-PDF_MARGIN_RIGHT);
    }

    public function Header()
    {
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();

        $this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);
        $this->Write(0, $headerdata['title']);

        $this->SetY(15);
        // set colors for gradients (r,g,b) or (grey 0-255)
        $blue = array(0, 141, 210);
        $white = array(255, 255, 255);

        // set the coordinates x1,y1,x2,y2 of the gradient (see linear_gradient_coords.jpg)
        $coords = array(0, 0, 1, 0);

        // paint a linear gradient
        $this->LinearGradient(PDF_MARGIN_LEFT, $this->y, $this->_widthWorkspacePage, 1, $blue, $white, $coords);
    }

    public function Footer()
    {
        if($this->_last_page_flag){
            $this->SetY(-15);
            $this->showSignature();
        }
    }

    /**
     * @param mixed $widthWorkspacePage
     * @return PdfService
     */
    public function setWidthWorkspacePage($widthWorkspacePage)
    {
        $this->_widthWorkspacePage = $widthWorkspacePage;
        return $this;
    }

    /**
     * @param boolean $last_page_flag
     * @return PdfService
     */
    public function setLastPageFlag($last_page_flag)
    {
        $this->_last_page_flag = $last_page_flag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidthWorkspacePage()
    {
        return $this->_widthWorkspacePage;
    }

    /**
     * @return boolean
     */
    public function isLastPageFlag()
    {
        return $this->_last_page_flag;
    }

    private function showSignature()
    {
        $image_file = __DIR__ .'/../../../../../data/images/pdf/alfa-hydro.png';
        $this->Image($image_file, $this->original_lMargin, $this->y, 50, '', 'PNG', '', 'M', true, 150, '', false, false, 0, false, false, false);

        $this->SetFontSize(10);
        $this->SetXY($this->x + 3, $this->y + 1);
        $this->SetFillColor(228,228,228);
        $numberPageWith = 20;
        $this->Cell($this->getPageWidth() - $this->x - $numberPageWith - 3, 7, 'www.alpha-hydro.com', 0, 0, 'C', true, 'http://alpha-hydro.com/catalog', 0, false, 'M');
        $this->SetX($this->x + 3);
        $this->SetFillColor(0,148,218);
        $this->SetTextColor(255);
        $this->SetFont('', 'B', 10);
        $this->Cell($numberPageWith, 7, $this->getAliasNumPage(), 0, 1, 'C', true, '', 0, false, 'M');
    }
}