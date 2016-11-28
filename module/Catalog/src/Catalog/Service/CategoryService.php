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
use Catalog\Model\Category;

class CategoryService implements CategoryServiceInterface
{
    /**
     * @var CategoryMapperInterface
     */
    protected $categoryMapper;

    public function __construct(CategoryMapperInterface $categoryMapper)
    {
        $this->categoryMapper = $categoryMapper;
    }

    public function fetchAll()
    {
        return $this->categoryMapper->fetchAllCategories();
    }

    public function find($id)
    {
        return $this->categoryMapper->findCategory($id);
    }

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
                $tree[] = $row;
            }
        }

        return $tree;
    }

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
}