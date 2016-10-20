<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2016, Alpha-Hydro
 *
 */

namespace Catalog\Model;


interface ProductInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getImage();

    /**
     * @return string
     */
    public function getSku();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getNote();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getFullPath();

    /**
     * @return string
     */
    public function getUploadPath();

    /**
     * @return string
     */
    public function getDraft();

    /**
     * @return string
     */
    public function getUploadPathDraft();
}