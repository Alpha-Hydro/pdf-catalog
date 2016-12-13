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
use Catalog\Mapper\ModificationMapperInterface;
use Catalog\Mapper\ModificationPropertyMapperInterface;
use Catalog\Mapper\ModificationPropertyValueMapperInterface;
use Catalog\Mapper\ProductMapperInterface;
use Catalog\Mapper\ProductModificationParamValuesMapperInterface;
use Catalog\Mapper\ProductParamsMapperInterface;
use Catalog\Model\ModificationPropertyValueInterface;
use Zend\Cache\Storage\Adapter\Filesystem;

class BaseService implements BaseServiceInterface
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
     * @var ProductParamsMapperInterface
     */
    protected $productParamsMapper;

    /**
     * @var ModificationMapperInterface
     */
    protected $modificationMapper;

    /**
     * @var ModificationPropertyMapperInterface
     */
    protected $modificationPropertyMapper;

    /**
     * @var ModificationPropertyValueInterface
     */
    protected $modificationPropertyValueMapper;

    /**
     * @var ProductModificationParamValuesMapperInterface
     */
    protected $productModificationParamValuesMapper;

    /**
     * @var Filesystem
     */
    protected $cache;

    /**
     * @var array
     */
    protected $_modification_table = [];


    public function __construct(
        CategoryMapperInterface $categoryMapper,
        ProductMapperInterface $productMapper,
        ProductParamsMapperInterface $productParamsMapper,
        ModificationMapperInterface $modificationMapper,
        ModificationPropertyMapperInterface $modificationPropertyMapper,
        ModificationPropertyValueMapperInterface $modificationPropertyValueMapper,
        ProductModificationParamValuesMapperInterface $modificationParamValuesMapper,
        Filesystem $cache
    )
    {
        $this->categoryMapper = $categoryMapper;
        $this->productMapper = $productMapper;
        $this->productParamsMapper = $productParamsMapper;
        $this->modificationMapper = $modificationMapper;
        $this->modificationPropertyMapper = $modificationPropertyMapper;
        $this->modificationPropertyValueMapper = $modificationPropertyValueMapper;
        $this->productModificationParamValuesMapper = $modificationParamValuesMapper;
        $this->cache = $cache;

        $this->_modification_table = $this->fetchAllProductModificationParamValues();
    }

    public function getProductById($id)
    {
        $keyCache = 'product_'.$id;
        $product = $this->cache->getItem($keyCache, $success);

        if(!$success){
            $product = $this->productMapper->findProduct($id, true);

            $productProperty = $this->productParamsMapper->fetchParamsByProduct($id);
            if(0 != $productProperty->count()){
                $product['property'] = $this->arrayGroupProductProperty($productProperty->toArray());
            }

            $productModification = $this->_modification_table[$id];
            if($productModification)
                $product['modifications'] = $productModification;

            $this->cache->setItem($keyCache, $product);
        }

        return $product;
    }

    /**
     * @return array
     */
    public function fetchAllProductModificationParamValues()
    {
        $array = $this->productModificationParamValuesMapper->fetchAllProductModificationParamValues();
        return $this->modificationTableValues($array);
    }

    /**
     * @param $array
     * @return array
     */
    private function modificationTableValues(&$array)
    {
        $result = [];

        $productsParam = $this->arrayGroupBy($array, ['product_id', 'param_name']);
        foreach ($productsParam as $id => $product){
            foreach ($product as $name => $param){
                $result[$id]['columns'][] = $name;
            }
        }

        $productsModification = $this->arrayGroupBy($array, ['product_id', 'modification_name']);
        foreach ($productsModification as $id => $modifications){
            foreach ($modifications as $n => $params){
                foreach ($params as $param){
                    $result[$id]['rows'][$n][] = $param['param_value'];
                }
            }
        }

        return $result;
    }


    /**
     * @param $array
     * @param array $keys
     * @return array
     */
    private function arrayGroupBy(&$array, $keys)
    {
        $result = [];

        $k = 0;
        $_key = $keys[$k];
        foreach ($array as $value){
            $key = $value[ $_key ];
            unset($value[$_key]);
            $result[$key][] = $value;
        }

        if(count($keys) > 1){
            array_shift($keys);
            foreach ($result as $key => $value){
                $result[$key] = $this->arrayGroupBy($value, $keys);
            }
        }
        return $result;
    }


    private function arrayGroupProductProperty($productProperty)
    {
        $result = [];
        foreach ($productProperty as $property){
            $result[$property['name']] = $property['value'];
        }

        return $result;
    }
}