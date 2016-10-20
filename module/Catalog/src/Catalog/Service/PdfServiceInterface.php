<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;

interface PdfServiceInterface
{
    public function defaultSettingsPage();

    public function introduction($html);

    public function tableOfContent($html);

    public function Output($name='doc.pdf', $dest='I');
}