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

    public function __construct(
        ProductMapperInterface $productMapper,
        ProductParamsMapperInterface $productParamsMapper,
        ModificationMapperInterface $modificationMapper,
        ModificationPropertyMapperInterface $modificationPropertyMapper,
        ModificationPropertyValueMapperInterface $modificationPropertyValueMapper,
        ProductModificationParamValuesMapperInterface $modificationParamValuesMapper
    )
    {
        $this->productMapper = $productMapper;
        $this->productParamsMapper = $productParamsMapper;
        $this->modificationMapper = $modificationMapper;
        $this->modificationPropertyMapper = $modificationPropertyMapper;
        $this->modificationPropertyValueMapper = $modificationPropertyValueMapper;
        $this->productModificationParamValuesMapper = $modificationParamValuesMapper;
    }

    /**
     * @return array|\Catalog\Model\ProductInterface[]
     */
    public function fetchAll()
    {
        return $this->productMapper->fetchAllProducts();
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
     * @return array|\Catalog\Model\ProductParamsInterface[]
     */
    public function fetchParamsByProduct($id)
    {
        return $this->productParamsMapper->fetchParamsByProduct($id);
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

    public function fetchAllProductsIsArray()
    {


        $result = [];
        /*$products = $this->fetchAll()->toArray();
        foreach ($products as $product){
            $productId = (int) $product['id'];
            $product['params'] = $this->fetchParamsByProduct($productId)->toArray();
//            $product['property'] = $this->fetchModificationPropertyByProduct($productId)->toArray();

//            $modifications = $this->fetchModificationsByProduct($productId);
//            $product['modifications'] = $this->modificationTableValues($modifications);
            $result[] = $product;
        }*/


        /*$key = 'modifications';
        $modifications = $this->cache->getItem($key, $success);
        if(!$success){
            $modifications = $this->modificationMapper->fetchAllModifications()->toArray();
            $this->cache->setItem($key, $modifications);
        }

        $key = 'modificationProperties';
        $modificationProperties = $this->cache->getItem($key, $success);
        if(!$success){
            $modificationProperties = $this->modificationPropertyMapper->fetchAllModificationProperties()->toArray();
            $this->cache->setItem($key, $modificationProperties);
        }*/

        /*$key = 'modificationPropertyValues';
        $modificationPropertyValues = $cache->getItem($key, $success);
        if(!$success){
            $modificationPropertyValues = $this->modificationPropertyValueMapper->fetchAllModificationPropertyValues()->toArray();
            $cache->setItem($key, $modificationPropertyValues);
        }*/


        //Debug::dump($cache->getOptions()); die();
        $modificationPropertyValues = $this->modificationPropertyValueMapper->fetchAllModificationPropertyValues();

        return $modificationPropertyValues;
    }

    /**
     * @return array
     */
    public function fetchAllModificationPropertyValues()
    {
        $array = $this->modificationPropertyValueMapper->fetchAllModificationPropertyValues();
        return $this->arrayGroupByModificationPropertyValues($array, ['product_id', 'subproduct_id']);
    }

    public function fetchAllProductModificationPapamValues()
    {
        return $this->productModificationParamValuesMapper->fetchAllProductModificationParamValues();
    }

    private function modificationTableValues($modifications)
    {
        $modificationsTableValues = array();
        $modificationsArray = $modifications->toArray();
        if(!empty($modificationsArray))
            foreach ($modificationsArray as $modification){
                $values = array();
                $values[] = $modification["sku"];
                $modificationPropertyValues = $this->fetchModificationPropertyValues($modification['id']);
                foreach ($modificationPropertyValues->toArray() as $modificationPropertyValue){
                    $values[] = $modificationPropertyValue['value'];
                }

                $modificationsTableValues[] = $values;
            }


        return $modificationsTableValues;
    }

    private function arrayGroupByModificationPropertyValues(&$array, $keys)
    {
        $result = [];

        $k = 0;
        $_key = $keys[$k];
        foreach ($array as $value){
            $key = $value[ $_key ];
            unset($value[$_key]);
            $result[$keys[$k].'_'.$key][] = $value;
        }

        if(count($keys) > 1){
            array_shift($keys);
            foreach ($result as $key => $value){
                $result[$key] = $this->arrayGroupByModificationPropertyValues($value, $keys);
            }
        }
        return $result;
    }

    private function array_group_by( array $array, $key )
    {
        if ( ! is_string( $key ) && ! is_int( $key ) && ! is_float( $key ) && ! is_callable( $key ) ) {
            trigger_error( 'array_group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR );
            return null;
        }
        $func = ( is_callable( $key ) ? $key : null );
        $_key = $key;
        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ( $array as $value ) {
            if ( is_callable( $func ) ) {
                $key = call_user_func( $func, $value );
            } elseif ( is_object( $value ) && isset( $value->{ $_key } ) ) {
                $key = $value->{ $_key };
            } elseif ( isset( $value[ $_key ] ) ) {
                $key = $value[ $_key ];
            } else {
                continue;
            }
            $grouped[ $key ][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if ( func_num_args() > 2 ) {
            $args = func_get_args();
            foreach ( $grouped as $key => $value ) {
                //$params = array_merge( [ $value ], array_slice( $args, 2, func_num_args() ) );
                //$grouped[ $key ] = call_user_func_array( 'array_group_by', array($params) );
                $grouped[ $key ] = $this->array_group_by($value, 'subproduct_id');
                //$grouped[ $key ] = $params;
            }
        }
        return $grouped;
    }
}