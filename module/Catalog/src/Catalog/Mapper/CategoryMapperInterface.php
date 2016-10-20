<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Mapper;

use Catalog\Model\CategoryInterface;

interface CategoryMapperInterface
{
    /**
     * @param $id
     * @return CategoryInterface
     * @throws \InvalidArgumentException
     */
    public function findCategory($id);

    /**
     * @return array|CategoryInterface[]
     */
    public function fetchAllCategories();
}