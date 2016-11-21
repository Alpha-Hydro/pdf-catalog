<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Mapper;


use Catalog\Model\ModificationPropertyInterface;

interface ModificationPropertyMapperInterface
{
    /**
     * @param $id
     * @return array | ModificationPropertyInterface[]
     */
    public function fetchModificationPropertiesByProduct($id);

    /**
     * @return array | ModificationPropertyInterface[]
     */
    public function fetchAllModificationProperties();
}