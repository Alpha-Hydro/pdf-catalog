<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


interface ModificationInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getParentId();

    /**
     * @return string
     */
    public function getSku();

}