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

interface ProductMapperInterface
{
    /**
     * @param $id
     * @return ProductInterface
     * throws \InvalidArgumentException
     */
    public function findProduct($id);

    /**
     * @return array | ProductInterface[]
     */
    public function fetchAllProducts();

    /**
     * @param $category_id
     * @return array | ProductInterface[]
     */
    public function fetchProductsByCategory($category_id);
}