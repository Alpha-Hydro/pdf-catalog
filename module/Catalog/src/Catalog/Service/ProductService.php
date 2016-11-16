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
use Catalog\Mapper\ProductMapperInterface;
use Catalog\Mapper\ProductParamsMapperInterface;

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

    public function __construct(
        ProductMapperInterface $productMapper,
        ProductParamsMapperInterface $productParamsMapper,
        ModificationMapperInterface $modificationMapper,
        ModificationPropertyMapperInterface $modificationPropertyMapper
    )
    {
        $this->productMapper = $productMapper;
        $this->productParamsMapper = $productParamsMapper;
        $this->modificationMapper = $modificationMapper;
        $this->modificationPropertyMapper = $modificationPropertyMapper;
    }

    public function fetchAll()
    {
        return $this->productMapper->fetchAllProducts();
    }

    public function find($id)
    {
        return $this->productMapper->findProduct($id);
    }

    public function fetchParamsByProduct($id)
    {
        return $this->productParamsMapper->fetchParamsByProduct($id);
    }

    public function fetchModificationsByProduct($id)
    {
        return $this->modificationMapper->fetchModificationsByProduct($id);
    }

    public function fetchModificationPropertyByProduct($id)
    {
        return $this->modificationPropertyMapper->fetchModificationPropertiesByProduct($id);
    }
}