<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Mapper;


use Catalog\Model\ModificationPropertyValueInterface;

interface ModificationPropertyValueMapperInterface
{
    /**
     * @param $modificationId
     * @param $propertyId
     * @return ModificationPropertyValueInterface
     * throws \InvalidArgumentException
     */
    public function getModificationPropertyValue($modificationId, $propertyId);

    /**
     * @param $id
     * @return array | ModificationPropertyValueInterface[]
     */
    public function fetchModificationPropertyValues($id);

    /**
     * @return array | ModificationPropertyValueInterface[]
     */
    public function fetchAllModificationPropertyValues();
}