<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;

use Catalog\Model\CategoryInterface;


interface CategoryServiceInterface
{
    /**
     * @return array|CategoryInterface[]
     */
    public function fetchAll();

    /**
     * @param $id int
     * @return CategoryInterface
     */
    public function find($id);

    /**
     * @param $parentId
     * @return array|CategoryInterface[]
     */
    public function findTreeByParentId($parentId);

    /**
     * @param $parentId
     * @return array|CategoryInterface[]
     */
    public function findCategoriesByParentId($parentId);
}