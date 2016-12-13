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
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Db\ResultSet\HydratingResultSet;
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

    /**
     * @var Filesystem
     */
    protected $cache;

    public function __construct(
        CategoryMapperInterface $categoryMapper,
        ProductMapperInterface $productMapper,
        Filesystem $cache
    )
    {
        $this->categoryMapper = $categoryMapper;
        $this->productMapper = $productMapper;
        $this->cache = $cache;
    }

    /**
     * @return array|HydratingResultSet
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
     * @return array|HydratingResultSet
     */
    public function fetchSubCategories($id)
    {
        return $this->categoryMapper->fetchSubCategories($id);
    }

    /**
     * @param $id
     * @param int $level
     * @return array
     */
    public function findTreeByParentId($id, $level = 0)
    {
        $result = array();
        $resultSet = $this->fetchSubCategories($id);
        $resultSet = $resultSet->toArray();

        foreach ($resultSet as $item) {
            $item['level'] = $level;
            $subCategories = $this->fetchSubCategories($item['id']);
            if(0 != $subCategories->count() && $level < 2){
                $item['sub_categories'] = $this->findTreeByParentId($item['id'], $level+1);
            }
            else{
                $keyCache = 'productsCategory_'.$item['id'];
                $productsCategory = $this->cache->getItem($keyCache, $success);

                if(!$success){
                    $productsCategory = $this->fetchAllProductsByCategory($item['id']);
                    $this->cache->setItem($keyCache, $productsCategory);
                }
                $item['products'] = $productsCategory;
            }
            $result[] = $item;
        }

        return $result;
    }

    /**
     * @param $id
     * @param null $result
     * @return array|null
     */
    public function fetchAllProductsByCategory($id, &$result = null){
        if(is_null($result))
            $result = array();

        $productCategory = $this->productMapper->fetchProductsByCategory($id);

        if(0 != $productCategory->count())
            $result = array_merge($result, $productCategory->toArray());

        $subCategories = $this->fetchSubCategories($id);
        if(0 != $subCategories->count()){
            foreach ($subCategories as $subCategory){
                $productSubCategory = $this->productMapper->fetchProductsByCategory($subCategory->getId());
                $result = array_merge($result, $productSubCategory->toArray());
                $children = $this->fetchSubCategories($subCategory->getId());
                if(0 != $children->count())
                    $this->fetchAllProductsByCategory($subCategory->getId(), $result);
            }
        }

        return $result;
    }

}