<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;

use Catalog\Model\ModificationInterface;
use Catalog\Model\ModificationPropertyInterface;
use Catalog\Model\ModificationPropertyValueInterface;
use Catalog\Model\ProductInterface;
use Catalog\Model\ProductParamsInterface;
use Zend\Db\ResultSet\HydratingResultSet;

interface ProductServiceInterface
{
    /**
     * @return array|HydratingResultSet
     */
    public function fetchAll();


    /**
     * @param $category_id
     * @return array|HydratingResultSet
     */
    public function fetchProductsByCategory($category_id);


    /**
     * @param $category_id
     * @return array
     */
    public function getFullArrayProductsByCategory($category_id);

    /**
     * @param $id int
     * @return ProductInterface
     */
    public function find($id);

    /**
     * @param $id
     * @return array
     */
    public function getFullInArray($id);

    /**
     * @param $id
     * @return array | HydratingResultSet
     */
    public function fetchParamsByProduct($id);

    /**
     * @return array
     */
    public function fetchAllProductParams();

    /**
     * @param $id
     * @return array | HydratingResultSet
     */
    public function fetchModificationsByProduct($id);

    /**
     * @param $id
     * @return array | HydratingResultSet
     */
    public function fetchModificationPropertyByProduct($id);

    /**
     * @param $modificationId
     * @param $propertyId
     * @return ModificationPropertyValueInterface
     */
    public function getModificationPropertyValue($modificationId, $propertyId);

    /**
     * @param $id
     * @return array | HydratingResultSet
     */
    public function fetchModificationPropertyValues($id);

    /**
     * @return array
     */
    public function fetchAllModificationPropertyValues();

    /**
     * @return array
     */
    public function fetchAllProductModificationParamValues();

}