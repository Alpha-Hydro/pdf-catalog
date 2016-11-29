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
use Catalog\Model\ModificationInterface;
use Catalog\Model\ModificationPropertyInterface;
use Catalog\Model\ModificationPropertyValueInterface;
use Catalog\Model\ProductInterface;
use Catalog\Model\ProductModificationParamValues;
use Catalog\Model\ProductParamsInterface;

use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements
    CategoryMapperInterface,
    ProductMapperInterface,
    ProductParamsMapperInterface,
    ModificationMapperInterface,
    ModificationPropertyMapperInterface,
    ModificationPropertyValueMapperInterface,
    ProductModificationParamValuesMapperInterface
{
    /**
     * @var AdapterInterface
     */
    protected $dbAdapter;

    /**
     * @var Filesystem
     */
    protected $cache;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var CategoryInterface
     */
    protected $categoryPrototype;

    /**
     * @var ProductInterface
     */
    protected $productPrototype;

    /**
     * @var ProductParamsInterface
     */
    protected $productParamsPrototype;

    /**
     * @var ModificationInterface
     */
    protected $modificationPrototype;

    /**
     * @var ModificationPropertyInterface
     */
    protected $modificationPropertyPrototype;

    /**
     * @var ModificationPropertyValueInterface
     */
    protected $modificationPropertyValuePrototype;

    /**
     * @var ModificationPropertyValueInterface
     */
    protected $productModificationParamValuesPrototype;


    /**
     * ZendDbSqlMapper constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(
        AdapterInterface $adapter,
        Filesystem $cache,
        HydratorInterface $hydrator,
        CategoryInterface $categoryPrototype,
        ProductInterface $productPrototype,
        ProductParamsInterface $productParamsPrototype,
        ModificationInterface $modificationPrototype,
        ModificationPropertyInterface $modificationPropertyPrototype,
        ModificationPropertyValueInterface $modificationPropertyValuePrototype,
        ProductModificationParamValues $productModificationParamValuesPrototype
    )
    {
        $this->dbAdapter = $adapter;
        $this->cache = $cache;
        $this->hydrator = $hydrator;
        $this->categoryPrototype = $categoryPrototype;
        $this->productPrototype = $productPrototype;
        $this->productParamsPrototype = $productParamsPrototype;
        $this->modificationPrototype = $modificationPrototype;
        $this->modificationPropertyPrototype = $modificationPropertyPrototype;
        $this->modificationPropertyValuePrototype = $modificationPropertyValuePrototype;
        $this->productModificationParamValuesPrototype = $productModificationParamValuesPrototype;
    }

    /**
     * @return array|HydratingResultSet
     */
    public function fetchAllCategories()
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('categories');
        $select
            ->where([
                'deleted != ?' => 1,
                'active != ?' => 0,
            ])
            ->order('sorting ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->categoryPrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }

    /**
     * @param $id
     * @return object CategoryInterface
     */
    public function findCategory($id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('categories');
        $select->where(array('id = ?' => $id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->categoryPrototype);
        }

        throw new \InvalidArgumentException("Category with given ID:{$id} not found.");
    }


    /**
     * @param $id
     * @return array|HydratingResultSet
     */
    public function fetchSubCategories($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('categories');
        $select
            ->where([
                'parent_id = ?' => $id,
                'deleted != ?' => 1,
                'active != ?' => 0,
            ])
            ->order('sorting ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->categoryPrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }


    /**
     * @return array|HydratingResultSet
     */
    public function fetchAllProducts()
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('products');
        $select
            ->where([
                'deleted != ?' => 1,
                'active != ?' => 0,
            ])
            ->join('categories_xref', 'products.id = categories_xref.product_id')
            //->limit(1000)
            ->order('sorting ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->productPrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }


    /**
     * @param $category_id
     * @return array|HydratingResultSet
     */
    public function fetchProductsByCategory($category_id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('products');
        $select
            ->join('categories_xref', 'products.id = categories_xref.product_id')
            ->where([
                'category_id = ?' => $category_id,
                'deleted != ?' => 1,
                'active != ?' => 0,
            ])
            ->order('sorting ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->productPrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }

    /**
     * @param $id
     * @return object
     */
    public function findProduct($id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('products');
        $select
            ->where(array('id = ?' => $id))
            ->join('categories_xref', 'products.id = categories_xref.product_id')
        ;

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->productPrototype);
        }

        throw new \InvalidArgumentException("Product with given ID:{$id} not found.");
    }

    /**
     * @param $id
     * @return array|HydratingResultSet
     */
    public function fetchParamsByProduct($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('product_params');
        $select->where(['product_id = ?' => $id])->order('order ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->productParamsPrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }

    public function fetchAllProductParams()
    {
        $keyCache = 'productParams';

        $productParams = $this->cache->getItem($keyCache, $success);

        if(!$success) {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('product_params');
            $select->order('order ASC');

            $stmt   = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {
                $resultSet = new HydratingResultSet($this->hydrator, $this->productParamsPrototype);
                $resultSet->initialize($result);

                $productParams = $resultSet->toArray();
                $this->cache->setItem($keyCache, $productParams);
            }
            else{
                $productParams = [];
            }
        }

        return $productParams;
    }

    /**
     * @param $id
     * @return array|HydratingResultSet
     */
    public function fetchModificationsByProduct($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('subproducts');
        $select
            ->where([
                'deleted != ?' => 1,
                'parent_id = ?' => $id
            ])
            ->order('order ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->modificationPrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }

    /**
     * @return array|HydratingResultSet
     */
    public function fetchAllModifications()
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('subproducts');
        $select
            ->where([
                'deleted != ?' => 1
            ])
            ->order('order ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->modificationPrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }

    /**
     * @param $id
     * @return array|HydratingResultSet
     */
    public function fetchModificationPropertiesByProduct($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('subproduct_params');
        $select
            ->where([
                'product_id = ?' => $id
            ])
            ->order('order ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->modificationPropertyPrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }

    /**
     * @return array|HydratingResultSet
     */
    public function fetchAllModificationProperties()
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('subproduct_params');
        $select->order('order ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->modificationPropertyPrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }

    /**
     * @param $modificationId
     * @param $propertyId
     * @return object
     */
    public function getModificationPropertyValue($modificationId, $propertyId)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('subproduct_params_values');
        $select
            ->where([
                'subproduct_id = ?' => $modificationId,
                'param_id = ?' => $propertyId
            ]);
        ;

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->modificationPropertyValuePrototype);
        }

        throw new \InvalidArgumentException("Property value not found.");
    }

    /**
     * @param $id
     * @return array|HydratingResultSet
     */
    public function fetchModificationPropertyValues($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('subproduct_params_values');
        $select
            ->join('subproduct_params', 'subproduct_params_values.param_id = subproduct_params.id')
            ->where([
                'subproduct_id = ?' => $id
            ])
            ->order('subproduct_params.order ASC');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->modificationPropertyValuePrototype);
            $resultSet->initialize($result);

            return $resultSet;
        }

        return array();
    }

    /**
     * @return array
     */
    public function fetchAllModificationPropertyValues()
    {
        $keyCache = 'modificationPropertyValues';

        $modificationPropertyValues = $this->cache->getItem($keyCache, $success);

        if(!$success) {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('subproduct_params_values');
            $select
                ->join('subproduct_params', 'subproduct_params_values.param_id = subproduct_params.id')
                //->limit(10000)
                ->order('subproduct_params.order ASC');

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {
                $resultSet = new HydratingResultSet($this->hydrator, $this->modificationPropertyValuePrototype);
                $resultSet->initialize($result);

                $modificationPropertyValues = $resultSet->toArray();
                $this->cache->setItem($keyCache, $modificationPropertyValues);
            }
            else{
                $modificationPropertyValues = array();
            }
        }

        return $modificationPropertyValues;
    }

    /**
     * @return array
     */
    public function fetchAllProductModificationParamValues()
    {
        $keyCache = 'productModificationParamValues';

        $productModificationParamValues = $this->cache->getItem($keyCache, $success);

        if(!$success) {
            $sql = new Sql($this->dbAdapter);
            $select = $sql->select('products');
            $select
                ->columns(['productId' => 'id'])
                ->join('subproduct_params', 'products.id = subproduct_params.product_id', ['paramName' => 'name'])
                ->join('subproduct_params_values', 'subproduct_params.id = subproduct_params_values.param_id', ['paramValue' => 'value'])
                ->join('subproducts', 'subproduct_params_values.subproduct_id = subproducts.id', ['modificationName' => 'sku'])
                ->where([
                    'products.deleted != ?' => 1,
                    'products.active != ?' => 0
                ])
                //->limit(100)
                ->order('subproduct_params.order ASC');

            $stmt = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {
                $resultSet = new HydratingResultSet($this->hydrator, $this->productModificationParamValuesPrototype);
                $resultSet->initialize($result);

                $productModificationParamValues = $resultSet->toArray();
                $this->cache->setItem($keyCache, $productModificationParamValues);
            }
            else{
                $productModificationParamValues = array();
            }
        }

        return $productModificationParamValues;
    }
}