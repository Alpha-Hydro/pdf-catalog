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
use Catalog\Model\ProductInterface;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements CategoryMapperInterface, ProductMapperInterface
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
     * ZendDbSqlMapper constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(
        AdapterInterface $adapter,
        HydratorInterface $hydrator,
        CategoryInterface $categoryPrototype,
        ProductInterface $productPrototype
    )
    {
        $this->dbAdapter = $adapter;
        $this->hydrator = $hydrator;
        $this->categoryPrototype = $categoryPrototype;
        $this->productPrototype = $productPrototype;
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
        $select->where(array('id = ?' => $id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->productPrototype);
        }

        throw new \InvalidArgumentException("Product with given ID:{$id} not found.");
    }
}