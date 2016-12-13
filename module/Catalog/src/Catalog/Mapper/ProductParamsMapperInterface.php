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
use Zend\Db\ResultSet\HydratingResultSet;

interface ProductParamsMapperInterface
{
    /**
     * @param $id
     * @return array | HydratingResultSet
     */
    public function fetchParamsByProduct($id);

    /**
     * @return array
     */
    public function fetchAllProductParams();
}