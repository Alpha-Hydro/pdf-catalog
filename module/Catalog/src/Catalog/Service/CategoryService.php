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
        return $this->categoryMapper->fetchAll();
    }

    public function find($id)
    {
        return $this->categoryMapper->find($id);
    }

    public function findTreeByParentId($id)
    {
        $resultSet = $this->categoryMapper->fetchAll();

        return $resultSet->toArray();
    }

    public function findCategoriesByParentId($id)
    {
        $resultSet = $this->categoryMapper->fetchAll();

        $subCategories = array();

        foreach($resultSet as $category){
            if($category->getParentId() === $id){
                $subCategories[] = $category;
            }
        }

        return $subCategories;
    }
}