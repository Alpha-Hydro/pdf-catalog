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

class ProductService implements ProductServiceInterface
{
    /**
     * @var ProductMapperInterface
     */
    protected $productMapper;

    public function __construct(ProductMapperInterface $productMapper)
    {
        $this->productMapper = $productMapper;
    }

    public function fetchAll()
    {
        return $this->productMapper->fetchAllProducts();
    }

    public function find($id)
    {
        return $this->productMapper->findProduct($id);
    }
}