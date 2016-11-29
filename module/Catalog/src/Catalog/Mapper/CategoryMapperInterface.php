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
use Zend\Db\ResultSet\HydratingResultSet;

interface CategoryMapperInterface
{
    /**
     * @param $id
     * @return CategoryInterface
     * @throws \InvalidArgumentException
     */
    public function findCategory($id);

    /**
     * @return array|HydratingResultSet
     */
    public function fetchAllCategories();


    /**
     * @param $id
     * @return array|HydratingResultSet
     */
    public function fetchSubCategories($id);
}