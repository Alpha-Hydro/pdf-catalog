<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;

use Catalog\Mapper\CategoryMapperInterface;
use Catalog\Mapper\ProductMapperInterface;
use Catalog\Model\Category;
use Zend\Debug\Debug;

class CategoryService implements CategoryServiceInterface
{
    /**
     * @var CategoryMapperInterface
     */
    protected $categoryMapper;

    /**
     * @var ProductMapperInterface
     */
    protected $productMapper;

    public function __construct(CategoryMapperInterface $categoryMapper, ProductMapperInterface $productMapper)
    {
        $this->categoryMapper = $categoryMapper;
        $this->productMapper = $productMapper;
    }

    /**
     * @return array|\Catalog\Model\CategoryInterface[]
     */
    public function fetchAll()
    {
        return $this->categoryMapper->fetchAllCategories();
    }

    /**
     * @param int $id
     * @return \Catalog\Model\CategoryInterface
     */
    public function find($id)
    {
        return $this->categoryMapper->findCategory($id);
    }

    /**
     * @param $id
     * @return array|\Catalog\Model\CategoryInterface[]
     */
    public function fetchSubCategories($id)
    {
        return $this->categoryMapper->fetchSubCategories($id);
    }

    /**
     * @param $id
     * @return array
     */
    public function findTreeByParentId($id)
    {
        $result = array();
        $resultSet = $this->categoryMapper->fetchAllCategories();
        $resultSet = $resultSet->toArray();

        foreach ($resultSet as $item) {
            $result[$item['parent_id']][] = $item;
        }

        $resultTree = $this->_tree_recurse($result, $result[$id]);

        return $resultTree;
    }

    /**
     * @param $id
     * @return array
     */
    public function findCategoriesByParentId($id)
    {
        $resultSet = $this->categoryMapper->fetchAllCategories();

        $subCategories = array();

        foreach($resultSet as $category){
            if($category->getParentId() === $id){
                $subCategories[] = $category;
            }
        }

        return $subCategories;
    }

    /**
     * @param $array
     * @param $parent array
     * @param int $level
     * @return array
     */
    protected function _tree_recurse(&$array, $parent, $level = 1)
    {
        $tree = array();

        if($parent){
            foreach ($parent as $row)
            {
                $row['level'] = $level;
                if(isset($array[$row['id']]))
                {
                    $level++;
                    $row['sub_categories'] = $this->_tree_recurse($array, $array[$row['id']], $level);
                    $level--;
                }
                else{
                    $row['products'] = $this->productMapper->fetchProductsByCategory($row['id'])->toArray();
                }
                $tree[] = $row;
            }
        }

        return $tree;
    }
}