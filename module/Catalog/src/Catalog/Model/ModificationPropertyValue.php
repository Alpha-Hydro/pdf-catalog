<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


class ModificationPropertyValue implements ModificationPropertyValueInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $subproductId;

    /**
     * @var int
     */
    protected $productId;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getSubproductId()
    {
        return $this->subproductId;
    }

    /**
     * @param int $subproductId
     */
    public function setSubproductId($subproductId)
    {
        $this->subproductId = $subproductId;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }
}