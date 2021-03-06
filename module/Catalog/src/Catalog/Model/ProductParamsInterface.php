<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


interface ProductParamsInterface
{
    /**
     * @return int
     */
    public function getProductId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();

}