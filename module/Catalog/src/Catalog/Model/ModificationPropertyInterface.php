<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


interface ModificationPropertyInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getProductId();

    /**
     * @return string
     */
    public function getName();

}