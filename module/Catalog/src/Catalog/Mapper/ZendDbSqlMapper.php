<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Mapper;

use Catalog\Model\Category;
use Catalog\Model\CategoryInterface;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements CategoryMapperInterface
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
     * ZendDbSqlMapper constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(
        AdapterInterface $adapter,
        HydratorInterface $hydrator,
        CategoryInterface $categoryPrototype
    )
    {
        $this->dbAdapter = $adapter;
        $this->hydrator = $hydrator;
        $this->categoryPrototype = $categoryPrototype;
    }

    public function fetchAll()
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

    public function find($id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('categories');
        $select->where(array('id = ?' => $id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->categoryPrototype);
        }

        throw new \InvalidArgumentException("Blog with given ID:{$id} not found.");
    }
}