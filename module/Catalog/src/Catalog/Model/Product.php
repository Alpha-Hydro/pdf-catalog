<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


class Product implements ProductInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $note;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $fullPath;

    /**
     * @var string
     */
    protected $uploadPath;

    /**
     * @var string
     */
    protected $draft;

    /**
     * @var string
     */
    protected $uploadPathDraft;

    /**
     * @var int
     */
    protected $categoryId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * @param string $fullPath
     */
    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;
    }

    /**
     * @return string
     */
    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    /**
     * @param string $uploadPath
     */
    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }

    /**
     * @return string
     */
    public function getDraft()
    {
        return $this->draft;
    }

    /**
     * @param string $draft
     */
    public function setDraft($draft)
    {
        $this->draft = $draft;
    }

    /**
     * @return string
     */
    public function getUploadPathDraft()
    {
        return $this->uploadPathDraft;
    }

    /**
     * @param string $uploadPathDraft
     */
    public function setUploadPathDraft($uploadPathDraft)
    {
        $this->uploadPathDraft = $uploadPathDraft;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

}