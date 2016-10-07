<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


interface CategoryInterface
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
    public function getName();

    /**
     * @return int
     */
    public function getActive();

    /**
     * @return string
     */
    public function getFullPath();


    /**
     * @return mixed
     */
    public function getSubCategories();

}