<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


interface ModificationPropertyValueInterface
{
    /**
     * @return string
     */
    public function getValue();

    /**
     * @return int
     */
    public function getSubproductId();

    /**
     * @return int
     */
    public function getProductId();

}