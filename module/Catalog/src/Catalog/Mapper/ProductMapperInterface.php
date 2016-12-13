<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Mapper;


use Catalog\Model\ProductInterface;
use Zend\Db\ResultSet\HydratingResultSet;

interface ProductMapperInterface
{
    /**
     * @param $id
     * @param $toArray
     * @return ProductInterface throws \InvalidArgumentException
     * throws \InvalidArgumentException
     */
    public function findProduct($id, $toArray = false);

    /**
     * @return array | HydratingResultSet
     */
    public function fetchAllProducts();

    /**
     * @param $category_id
     * @return array | HydratingResultSet
     */
    public function fetchProductsByCategory($category_id);
}