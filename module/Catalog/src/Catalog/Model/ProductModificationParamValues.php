<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


class ProductModificationParamValues implements ProductModificationParamValuesInterface
{
    /**
     * @var int
     */
    protected $productId;

    /**
     * @var string
     */
    protected $modificationName;

    /**
     * @var string
     */
    protected $paramName;

    /**
     * @var string
     */
    protected $paramValue;

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

    /**
     * @return string
     */
    public function getModificationName()
    {
        return $this->modificationName;
    }

    /**
     * @param string $modificationName
     */
    public function setModificationName($modificationName)
    {
        $this->modificationName = $modificationName;
    }

    /**
     * @return string
     */
    public function getParamName()
    {
        return $this->paramName;
    }

    /**
     * @param string $paramName
     */
    public function setParamName($paramName)
    {
        $this->paramName = $paramName;
    }

    /**
     * @return string
     */
    public function getParamValue()
    {
        return $this->paramValue;
    }

    /**
     * @param string $paramValue
     */
    public function setParamValue($paramValue)
    {
        $this->paramValue = $paramValue;
    }
}