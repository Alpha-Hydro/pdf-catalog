<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Mapper;

use Catalog\Model\ProductParamsInterface;

interface ProductParamsMapperInterface
{
    /**
     * @param $id
     * @return array | ProductParamsInterface[]
     */
    public function fetchParamsByProduct($id);
}