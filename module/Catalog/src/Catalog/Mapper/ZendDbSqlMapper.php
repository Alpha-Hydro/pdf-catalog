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
use Catalog\Model\ProductParamsInterface;

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
    ModificationPropertyValueMapperInterface
{
    /**
     * @var AdapterInterface
     */
    protected $dbAdapter;

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
     * ZendDbSqlMapper constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(
        AdapterInterface $adapter,
        HydratorInterface $hydrator,
        CategoryInterface $categoryPrototype,
        ProductInterface $productPrototype,
        ProductParamsInterface $productParamsPrototype,
        ModificationInterface $modificationPrototype,
        ModificationPropertyInterface $modificationPropertyPrototype,
        ModificationPropertyValueInterface $modificationPropertyValuePrototype
    )
    {
        $this->dbAdapter = $adapter;
        $this->hydrator = $hydrator;
        $this->categoryPrototype = $categoryPrototype;
        $this->productPrototype = $productPrototype;
        $this->productParamsPrototype = $productParamsPrototype;
        $this->modificationPrototype = $modificationPrototype;
        $this->modificationPropertyPrototype = $modificationPropertyPrototype;
        $this->modificationPropertyValuePrototype = $modificationPropertyValuePrototype;
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
     * @return object
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
}