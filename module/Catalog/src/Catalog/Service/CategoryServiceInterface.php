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
use Zend\Db\ResultSet\HydratingResultSet;


interface CategoryServiceInterface
{
    /**
     * @return array|HydratingResultSet
     */
    public function fetchAll();

    /**
     * @param $id int
     * @return CategoryInterface
     */
    public function find($id);

    /**
     * @param $id
     * @return array|HydratingResultSet
     */
    public function fetchSubCategories($id);

    /**
     * @param $parentId
     * @return array|HydratingResultSet
     */
    public function findTreeByParentId($parentId);

    /**
     * @param $parentId
     * @return array|HydratingResultSet
     */
    public function findCategoriesByParentId($parentId);

    /**
     * @param $id
     * @return array
     */
    public function fetchAllProductsByCategory($id);
}