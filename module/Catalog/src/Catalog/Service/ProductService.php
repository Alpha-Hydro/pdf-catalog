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

    public function __construct(
        ProductMapperInterface $productMapper,
        ProductParamsMapperInterface $productParamsMapper,
        ModificationMapperInterface $modificationMapper,
        ModificationPropertyMapperInterface $modificationPropertyMapper,
        ModificationPropertyValueMapperInterface $modificationPropertyValueMapper
    )
    {
        $this->productMapper = $productMapper;
        $this->productParamsMapper = $productParamsMapper;
        $this->modificationMapper = $modificationMapper;
        $this->modificationPropertyMapper = $modificationPropertyMapper;
        $this->modificationPropertyValueMapper = $modificationPropertyValueMapper;
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
}