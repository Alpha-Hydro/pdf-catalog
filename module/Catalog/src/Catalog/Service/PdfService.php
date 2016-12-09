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
use TCPDF_FONTS;
use TCPDF_STATIC;
use Zend\Debug\Debug;

class PdfService extends TCPDF
{
    /**
     * @var int
     */
    protected $_widthWorkspacePage;

    /**
     * @var string
     */
    protected $_image_field_bookmark;

    /**
     * @var array
     */
    protected $_product_property = [];

    /**
     * @var array
     */
    protected $_product_table_modification = [];

    public function __construct()
    {
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->setWidthWorkspacePage($this->getPageWidth()-(PDF_MARGIN_LEFT+PDF_MARGIN_RIGHT));
        $this->setImageFieldBookmark('fieldBookmark.png');
    }

    public function Header()
    {
        $headerfont = $this->getHeaderFont();
        $headerdata = $this->getHeaderData();

        $this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);

        $align = ($this->getNumPages() % 2 === 0)?'':'R';

        $this->Cell(0,0,$headerdata['title'], 0, 0, $align);

        $this->SetY(15);
        // set colors for gradients (r,g,b) or (grey 0-255)
        $blue = array(0, 141, 210);
        $white = array(255, 255, 255);

        // set the coordinates x1,y1,x2,y2 of the gradient (see linear_gradient_coords.jpg)
        $coords = array(0, 0, 1, 0);
        $wl = $this->w-$this->lMargin-$this->rMargin;

        if ($this->getNumPages() % 2 === 0){
            $this->LinearGradient($this->lMargin, $this->y, $wl, 1, $blue, $white, $coords);
        }
        else{
            $this->LinearGradient($this->lMargin, $this->y, $wl, 1, $white, $blue, $coords);
        }

        $this->fieldBookmarks();
    }

    public function Footer()
    {
        $this->SetY(-15);

        if($this->getNumPages() % 2 === 0){
            $this->showFooterEvenPage();
        }
        else{
            $this->showFooterOddPage();
        }
    }

    public function Output($name='doc.pdf', $dest='I')
    {
        parent::Output($name, $dest);
    }

    public function addTOC($page='', $numbersfont='', $filler='.', $toc_name='TOC', $style='', $color=array(0,0,0)) {
        $fontsize = $this->FontSizePt;
        $fontfamily = $this->FontFamily;
        $fontstyle = $this->FontStyle;
        $w = $this->w - $this->lMargin - $this->rMargin;
        $spacer = $this->GetStringWidth(chr(32)) * 4;
        $lmargin = $this->lMargin;
        $rmargin = $this->rMargin;
        $x_start = $this->GetX();
        $page_first = $this->page;
        $current_page = $this->page;
        $page_fill_start = false;
        $page_fill_end = false;
        $current_column = $this->current_column;
        if (TCPDF_STATIC::empty_string($numbersfont)) {
            $numbersfont = $this->default_monospaced_font;
        }
        if (TCPDF_STATIC::empty_string($filler)) {
            $filler = ' ';
        }
        if (TCPDF_STATIC::empty_string($page)) {
            $gap = ' ';
        } else {
            $gap = '';
            if ($page < 1) {
                $page = 1;
            }
        }
        $this->SetFont($numbersfont, $fontstyle, $fontsize);
        $numwidth = $this->GetStringWidth('00000');
        $maxpage = 0; //used for pages on attached documents

        foreach ($this->outlines as $key => $outline) {

            //Debug::dump($current_column);
            // check for extra pages (used for attachments)
            if (($this->page > $page_first) AND ($outline['p'] >= $this->numpages)) {
                $outline['p'] += ($this->page - $page_first);
            }
            if ($this->rtl) {
                $aligntext = 'R';
                $alignnum = 'L';
            } else {
                $aligntext = 'L';
                $alignnum = 'R';
            }
            if ($outline['l'] == 0) {
                $this->SetFont($fontfamily, $outline['s'].'B', $fontsize);
            } else {
                $this->SetFont($fontfamily, $outline['s'], $fontsize - $outline['l']);
            }
            $this->SetTextColorArray($outline['c']);
            // check for page break
            $this->checkPageBreak(2 * $this->getCellHeight($this->FontSize));
            // set margins and X position
            if (($this->page == $current_page) AND ($this->current_column == $current_column)) {
                $this->lMargin = $lmargin;
                $this->rMargin = $rmargin;
            } else {
                if ($this->current_column != $current_column) {
                    if ($this->rtl) {
                        $x_start = $this->w - $this->columns[$this->current_column]['x'];
                    } else {
                        $x_start = $this->columns[$this->current_column]['x'];
                    }
                }
                $lmargin = $this->lMargin;
                $rmargin = $this->rMargin;
                $current_page = $this->page;
                $current_column = $this->current_column;
            }

            $x_start = ($this->booklet)?$this->lMargin:$x_start;
            $this->SetX($x_start);

            $indent = ($spacer * $outline['l']);
            if ($this->rtl) {
                $this->x -= $indent;
                $this->rMargin = $this->w - $this->x;
            } else {
                $this->x += $indent;
                $this->lMargin = ($this->booklet)?$this->lMargin:$this->x;
            }
            $link = $this->AddLink();
            $this->SetLink($link, $outline['y'], $outline['p']);
            // write the text
            if ($this->rtl) {
                $txt = ' '.$outline['t'];
            } else {
                $txt = $outline['t'].' ';
            }
            $this->Write(0, $txt, $link, false, $aligntext, false, 0, false, false, 0, $numwidth, '');
            if ($this->rtl) {
                $tw = $this->x - $this->lMargin;
            } else {
                $tw = $this->w - $this->rMargin - $this->x;
            }
            $this->SetFont($numbersfont, $fontstyle, $fontsize);
            if (TCPDF_STATIC::empty_string($page)) {
                $pagenum = $outline['p'];
            } else {
                // placemark to be replaced with the correct number
                $pagenum = '{#'.($outline['p']).'}';
                if ($this->isUnicodeFont()) {
                    $pagenum = '{'.$pagenum.'}';
                }
                $maxpage = max($maxpage, $outline['p']);
            }
            $fw = ($tw - $this->GetStringWidth($pagenum.$filler));
            $wfiller = $this->GetStringWidth($filler);
            if ($wfiller > 0) {
                $numfills = floor($fw / $wfiller);
            } else {
                $numfills = 0;
            }
            if ($numfills > 0) {
                $rowfill = str_repeat($filler, $numfills);
            } else {
                $rowfill = '';
            }
            if ($this->rtl) {
                $pagenum = $pagenum.$gap.$rowfill;
            } else {
                $pagenum = $rowfill.$gap.$pagenum;
            }

            // write the number
            $this->Cell($tw, 0, $pagenum, 0, 1, $alignnum, 0, $link, 0);
        }
        $page_last = $this->getPage();
        $numpages = ($page_last - $page_first + 1);


        if ($this->booklet) {
            // check if a blank page is required before TOC
            $page_fill_start = ((($page_first % 2) == 0) XOR (($page % 2) == 0));
            $page_fill_end = (!((($numpages % 2) == 0) XOR ($page_fill_start)));
            if ($page_fill_start) {
                // add a page at the end (to be moved before TOC)
                $this->addPage();
                ++$page_last;
                ++$numpages;
            }
            if ($page_fill_end) {
                // add a page at the end
                $this->addPage();
                ++$page_last;
                ++$numpages;
            }

        }
        $maxpage = max($maxpage, $page_last);
        if (!TCPDF_STATIC::empty_string($page)) {
            for ($p = $page_first; $p <= $page_last; ++$p) {
                // get page data
                $temppage = $this->getPageBuffer($p);
                for ($n = 1; $n <= $maxpage; ++$n) {
                    // update page numbers
                    $a = '{#'.$n.'}';
                    // get page number aliases
                    $pnalias = $this->getInternalPageNumberAliases($a);
                    // calculate replacement number
                    if (($n >= $page) AND ($n <= $this->numpages)) {
                        $np = $n + $numpages;
                    } else {
                        $np = $n;
                    }
                    $na = TCPDF_STATIC::formatTOCPageNumber(($this->starting_page_number + $np - 1));
                    $nu = TCPDF_FONTS::UTF8ToUTF16BE($na, false, $this->isunicode, $this->CurrentFont);
                    // replace aliases with numbers
                    foreach ($pnalias['u'] as $u) {
                        $sfill = str_repeat($filler, max(0, (strlen($u) - strlen($nu.' '))));
                        if ($this->rtl) {
                            $nr = $nu.TCPDF_FONTS::UTF8ToUTF16BE(' '.$sfill, false, $this->isunicode, $this->CurrentFont);
                        } else {
                            $nr = TCPDF_FONTS::UTF8ToUTF16BE($sfill.' ', false, $this->isunicode, $this->CurrentFont).$nu;
                        }
                        $temppage = str_replace($u, $nr, $temppage);
                    }
                    foreach ($pnalias['a'] as $a) {
                        $sfill = str_repeat($filler, max(0, (strlen($a) - strlen($na.' '))));
                        if ($this->rtl) {
                            $nr = $na.' '.$sfill;
                        } else {
                            $nr = $sfill.' '.$na;
                        }
                        $temppage = str_replace($a, $nr, $temppage);
                    }
                }
                // save changes
                $this->setPageBuffer($p, $temppage);
            }
            // move pages
            $this->Bookmark($toc_name, 0, 0, $page_first, $style, $color);
            if ($page_fill_start) {
                $this->movePage($page_last, $page_first);
            }
            for ($i = 0; $i < $numpages; ++$i) {
                $this->movePage($page_last, $page);
            }
        }
    }


    public function defaultSettingsPage()
    {
        $this->SetCreator('Alpha-Hydro');
        $this->SetAuthor('Alpha-Hydro');
        $this->SetTitle('Alpha-Hydro. Каталог товаров.');
        $this->SetSubject('Alpha-Hydro');
        $this->SetKeywords('Alpha-Hydro, PDF, каталог, гидравлика');

        // set header and footer fonts
        $this->setHeaderFont(array('arialnarrow', '', 12));
        $this->setFooterFont(array('arialnarrow', '', 12));

        // set default monospaced font
        $this->SetDefaultMonospacedFont('freeserif');

        // set margins
        $this->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $this->SetAutoPageBreak(TRUE, 20);

        // set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // set booklet mode
        $this->SetBooklet(true, 15, 20);

        $this->SetFont('arialnarrow', '', 12, '', false);

        return $this;
    }

    public function introduction($html)
    {
        $this->SetHeaderData('', 0, 'Введение', '');
        $this->AddPage();
        $this->writeHTML($html);
        $this->lastPage();

        return $this;
    }

    public function tableOfContent($treeCategories)
    {
        //set_time_limit(1800);
        foreach ($treeCategories as $category1){
            $this->SetHeaderData('', 0, $category1['name'], '');
            $this->setImageFieldBookmark('fieldBookmark_'.$category1['id'].'.png');
            $this->AddPage();
            $this->Bookmark($category1['name'], 0 , 0, '', 'B', [237, 133, 31]);
            if($category1['sub_categories']){

                foreach ($category1['sub_categories'] as $category2){
                    if($category2['sub_categories']){
                        $this->SetHeaderData('', 0, $category2['name'], '');
                        $this->Bookmark($category2['name'], 1 , 0, '', 'B', [0, 0, 0]);
                        foreach ($category2['sub_categories'] as $category3){
                            if($category3['products']){
                                $this->AddPage();
                                $this->Bookmark($category3['name'], 2 , 0, '', '', [0, 0, 0]);
                                $this->Cell(0, 0, $category3['name'], 0, 1, 'L');
                                foreach ($category3['products'] as $product){
                                    $this->viewProduct($product);
                                }
                            }
                        }
                    }
                }
            }
            //$this->SetHeaderData('', 0, $category1['name'], '');
        }

        $this->SetHeaderData('', 0, 'Содержание', '');
        $this->addTOCPage();
        $this->SetFont('arialnarrow', '', 12);
        $this->addTOC(2, 'arialnarrow', '.', 'Содержание', '', array(128,0,0));
        $this->endTOCPage();

        return $this;
    }

    public function viewProduct($product)
    {
        $this->SetFont('arialnarrow', 'B', 16);
        $this->Cell(0, 0, $product['sku'], 0, 1, 'L');


        $this->SetFontSize(12);
        //$this->SetDrawColor(237, 133, 31); //orange
        //$this->SetDrawColor(0, 141, 210); //blue
        $this->SetDrawColor(160,160,160); //gray
        $this->SetLineWidth(0.1);
        $this->Cell(0, 0, $product['name'], 'B', 1, 'L');

        $this->Ln(5);

        $image_file = __DIR__ .'/../../../../..'.$product['upload_path'].$product['image'];
        $this->Image($image_file,$this->x,$this->y,'', 25, '', '', 'T');

        $this->SetX($this->x + 5);

        if($product['draft']){
            $image_draft = __DIR__ .'/../../../../..'.$product['upload_path_draft'].$product['draft'];
            if(file_exists($image_draft))
                $this->Image($image_draft,$this->x,$this->y, '', 25, '', '', 'T',true,190);
            $this->SetX($this->x + 5);
        }

        $productProperty = $this->getProductProperty($product['id']);

        $x = $this->getImageRBX()+5;
        if(!empty($productProperty)){
            $w = array(30, $this->getPageWidth()-$this->original_rMargin-$x-30);
            foreach ($productProperty as $property){
                $this->SetFont('','B',8);
                $this->MultiCell($w[0], 0, $property['name'], 0, 'L', false, 0, $x, '', true, 0, false, true, 0);

                $this->SetFont('','',8);
                //$value =
                $this->MultiCell($w[1], 0, $property['value'], 0, 'L', false, 0, '', '', true, 0, false, true, 0);

                $this->Ln();
            }
        }

        $this->Ln(5);
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
     * @return mixed
     */
    public function getWidthWorkspacePage()
    {
        return $this->_widthWorkspacePage;
    }

    /**
     * @return mixed
     */
    public function getImageFieldBookmark()
    {
        return $this->_image_field_bookmark;
    }

    /**
     * @param mixed $image_field_bookmark
     */
    public function setImageFieldBookmark($image_field_bookmark)
    {
        $image_path = __DIR__ .'/../../../../../data/images/pdf/';
        $image = $image_path.$image_field_bookmark;

        $this->_image_field_bookmark = (file_exists($image))?$image: $image_path.'fieldBookmark.png';
    }

    /**
     * @param null $id
     * @return array
     */
    public function getProductProperty($id = null)
    {
        if(!is_null($id))
            return $this->_product_property[$id];

        return $this->_product_property;
    }

    /**
     * @param array $product_property
     */
    public function setProductProperty($product_property)
    {
        $this->_product_property = $product_property;
    }

    /**
     * @return array
     */
    public function getProductTableModification()
    {
        return $this->_product_table_modification;
    }

    /**
     * @param array $product_table_modification
     */
    public function setProductTableModification($product_table_modification)
    {
        $this->_product_table_modification = $product_table_modification;
    }

    private function showFooterEvenPage()
    {
        $image_file = __DIR__ .'/../../../../../data/images/pdf/alfa-hydro.png';
        $this->Image($image_file, $this->original_lMargin, $this->y, 50, '', 'PNG', '', 'M', true, 150, '', false, false, 0, false, false, false);

        $this->SetFontSize(10);
        $this->SetXY($this->x + 3, $this->y + 1);
        $this->SetFillColor(228,228,228);
        $numberPageWith = 20;
        $this->Cell($this->getPageWidth() - $this->x - $numberPageWith - 3, 7, 'www.alpha-hydro.com', 0, 0, 'C', true, 'http://alpha-hydro.com/catalog', 0, false, 'M');
        $this->SetX($this->x + 3);

        //Номер страницы
        $this->setCellPaddings(5, 0, 0, 0);
        $this->SetFillColor(0,148,218);
        $this->SetTextColor(255);
        $this->SetFont('', 'B', 10);
        $this->Cell($numberPageWith, 7, $this->getAliasNumPage(), 0, 1, 'L', true, '', 0, false, 'M');
    }

    private function showFooterOddPage()
    {
        $this->SetFont('', 'B', 10);

        //Номер страницы
        $numberPageWith = 20;
        $this->SetXY(0, $this->y +6);
        $this->setCellPaddings(0, 0, 5, 0);
        $this->SetFillColor(0,148,218);
        $this->SetTextColor(255);
        $this->Cell($numberPageWith, 7, $this->getAliasNumPage(), 0, 0, 'R', true, '', 0, false, 'M');

        //Строка
        $this->SetX($this->x + 3);
        $this->SetFont('');
        $this->SetFillColor(228,228,228);
        $this->SetTextColor(0);
        $this->Cell($this->getPageWidth() - $this->x - $this->original_rMargin - 53, 7, 'www.alpha-hydro.com', 0, 0, 'C', true, 'http://alpha-hydro.com/catalog', 0, false, 'M');

        //Логотип
        $this->SetX($this->x +3);
        $image_file = __DIR__ .'/../../../../../data/images/pdf/alfa-hydro.png';
        $this->Image($image_file, $this->x, $this->y -6, 50, '', 'PNG', '', 'M', true, 150, '', false, false, 0, false, false, false);
    }

    private function fieldBookmarks(){
        $template_id = $this->startTemplate(14, 0, true);

        $image_file = $this->getImageFieldBookmark();
        if($this->getNumPages() % 2 === 0){
            $this->Image($image_file, 0, 0, 14, 0, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
        }
        else{
            $this->StartTransform();
            $this->MirrorH();
            $this->Image($image_file, -14, 0, 14, 0, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
            $this->StopTransform();
        }

        $this->endTemplate();

        $x = ($this->getNumPages() % 2 === 0)?$this->w - 13:0;

        $this->printTemplate($template_id, $x, 20, 0, 0, '', '', false);
    }

}