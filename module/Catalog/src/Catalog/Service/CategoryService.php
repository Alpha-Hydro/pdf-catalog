<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Service;


use Catalog\Model\Category;

class CategoryService implements CategoryServiceInterface
{
    protected $data = [
        [
            'id'    => 1,
            'parent_id'    => 0,
            'name' => 'Соединительная арматура',
            'active'  => 1,
            'full_path'  => 'soedinitelnaja-armatura',
            'sorting'  => 0,
            'deleted'  => 0,
        ],
        [
            'id'    => 2,
            'parent_id'    => 0,
            'name' => 'Скобы',
            'active'  => 1,
            'full_path'  => 'skoby',
            'sorting'  => 0,
            'deleted'  => 0,
        ],
        [
            'id'    => 3,
            'parent_id'    => 0,
            'name' => 'Шланги',
            'active'  => 1,
            'full_path'  => 'shlangi',
            'sorting'  => 0,
            'deleted'  => 0,
        ],
        [
            'id'    => 4,
            'parent_id'    => 0,
            'name' => 'Краны',
            'active'  => 1,
            'full_path'  => 'krany',
            'sorting'  => 0,
            'deleted'  => 0,
        ],
        [
            'id'    => 5,
            'parent_id'    => 0,
            'name' => 'Манометры',
            'active'  => 1,
            'full_path'  => 'manometry-i-prinadlezhnosti',
            'sorting'  => 0,
            'deleted'  => 0,
        ],

    ];

    public function fetchAll()
    {
        $allCategory = array();

        foreach ($this->data as $index => $category){
            $allCategory[] = $this->find($index);
        }

        return $allCategory;
    }

    public function find($id)
    {
        $categoryData = $this->data[$id];

        $model = new Category();

        $model->setId($categoryData['id']);
        $model->setName($categoryData['name']);
        $model->setParentId($categoryData['parent_id']);
        $model->setActive($categoryData['active']);
        $model->setFullPath($categoryData['full_path']);
        $model->setSorting($categoryData['sorting']);
        $model->setDeleted($categoryData['deleted']);

        return $model;
    }
}