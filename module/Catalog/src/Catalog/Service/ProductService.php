<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


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

    public function __construct(
        ProductMapperInterface $productMapper,
        ProductParamsMapperInterface $productParamsMapper
    )
    {
        $this->productMapper = $productMapper;
        $this->productParamsMapper = $productParamsMapper;
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
}