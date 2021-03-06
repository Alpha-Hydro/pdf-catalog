<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


use Catalog\Mapper\ModificationMapperInterface;
use Catalog\Mapper\ModificationPropertyMapperInterface;
use Catalog\Mapper\ModificationPropertyValueMapperInterface;
use Catalog\Mapper\ProductMapperInterface;
use Catalog\Mapper\ProductModificationParamValuesMapperInterface;
use Catalog\Mapper\ProductParamsMapperInterface;
use Catalog\Model\ModificationPropertyValueInterface;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Db\ResultSet\HydratingResultSet;

class ProductService implements ProductServiceInterface
{
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
    protected $_product_params = [];

    /**
     * @var array
     */
    protected $_modification_table = [];

    public function __construct(
        ProductMapperInterface $productMapper,
        ProductParamsMapperInterface $productParamsMapper,
        ModificationMapperInterface $modificationMapper,
        ModificationPropertyMapperInterface $modificationPropertyMapper,
        ModificationPropertyValueMapperInterface $modificationPropertyValueMapper,
        ProductModificationParamValuesMapperInterface $modificationParamValuesMapper,
        Filesystem $cache
    )
    {
        $this->productMapper = $productMapper;
        $this->productParamsMapper = $productParamsMapper;
        $this->modificationMapper = $modificationMapper;
        $this->modificationPropertyMapper = $modificationPropertyMapper;
        $this->modificationPropertyValueMapper = $modificationPropertyValueMapper;
        $this->productModificationParamValuesMapper = $modificationParamValuesMapper;
        $this->cache = $cache;

        $this->_product_params = $this->fetchAllProductParams();
        $this->_modification_table = $this->fetchAllProductModificationParamValues();
    }

    /**
     * @return array|HydratingResultSet
     */
    public function fetchAll()
    {
        return $this->productMapper->fetchAllProducts();
    }

    /**
     * @param $category_id
     * @return array|HydratingResultSet
     */
    public function fetchProductsByCategory($category_id)
    {
        return $this->productMapper->fetchProductsByCategory($category_id);
    }


    public function getFullArrayProductsByCategory($category_id)
    {
        $result = [];
        $productsCategory = $this->productMapper->fetchProductsByCategory($category_id);

        if(0 != $productsCategory->count()){
            foreach ($productsCategory as $product){
                $result[] = $this->getFullInArray($product->getId());
            }
        }

        return $result;
    }

    /**
     * @param int $id
     * @return \Catalog\Model\ProductInterface
     */
    public function find($id)
    {
        return $this->productMapper->findProduct($id);
    }

    /**
     * @param $id
     * @return array
     */
    public function getFullInArray($id)
    {
        $keyCache = 'product_'.$id;
        $product = $this->cache->getItem($keyCache, $success);

        if(!$success){
            $product = $this->productMapper->findProduct($id, true);

            $productProperty = $this->_product_params[$id];
            if($productProperty)
                $product['property'] = $this->arrayGroupProductProperty($productProperty);

            $productModification = $this->_modification_table[$id];
            if($productModification)
                $product['modifications'] = $productModification;

            $this->cache->setItem($keyCache, $product);
        }

        return $product;
    }

    /**
     * @param $id
     * @return array|\Catalog\Model\ProductParamsInterface[]
     */
    public function fetchParamsByProduct($id)
    {
        return $this->productParamsMapper->fetchParamsByProduct($id);
    }

    /**
     * @return array
     */
    public function fetchAllProductParams(){
        $productParams = $this->productParamsMapper->fetchAllProductParams();
        return $this->arrayGroupBy($productParams, ['product_id']);
    }

    /**
     * @param $id
     * @return array|\Catalog\Model\ModificationInterface[]
     */
    public function fetchModificationsByProduct($id)
    {
        return $this->modificationMapper->fetchModificationsByProduct($id);
    }

    /**
     * @param $id
     * @return array|\Catalog\Model\ModificationPropertyInterface[]
     */
    public function fetchModificationPropertyByProduct($id)
    {
        return $this->modificationPropertyMapper->fetchModificationPropertiesByProduct($id);
    }

    /**
     * @param $modificationId
     * @param $propertyId
     * @return ModificationPropertyValueInterface
     */
    public function getModificationPropertyValue($modificationId, $propertyId)
    {
        return $this->modificationPropertyValueMapper->getModificationPropertyValue($modificationId, $propertyId);
    }

    /**
     * @param $modificationId
     * @return array|\Catalog\Model\ModificationPropertyValueInterface[]
     */
    public function fetchModificationPropertyValues($modificationId)
    {
        return $this->modificationPropertyValueMapper->fetchModificationPropertyValues($modificationId);
    }


    /**
     * @return array
     */
    public function fetchAllModificationPropertyValues()
    {
        return $this->modificationPropertyValueMapper->fetchAllModificationPropertyValues();
    }

    /**
     * @return array
     */
    public function fetchAllProductModificationParamValues()
    {
        $array = $this->productModificationParamValuesMapper->fetchAllProductModificationParamValues();
        return $this->modificationTableValues($array);
    }



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