<?php

/**
 *
 * Copyright (c) 2010, Sven Eisenschmidt.
 * All rights reserved.
 *
 * Redistribution with or without modification, are permitted.
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category ResultSet
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses Phoursquare_CategoriesList
 */

/**
 * Phoursquare_Category
 *
 * @category ResultSet
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Category {

    /**
     *
     * @var Phoursquare_Service
     */
    protected $_service;

    /**
     *
     * @var integer
     */
    protected $_id;

    /**
     *
     * @var string
     */
    protected $_nodename;

    /**
     *
     * @var string
     */
    protected $_fullpathname;

    /**
     *
     * @var string
     */
    protected $_iconurl;

    /**
     *
     * @var array
     */
    protected $_categories;
    
    /**
     *
     * @var Phoursquare_Category
     */
    protected $_parent;

    /**
     *
     * @param stdClass $data
     * @param Phoursquare_Venue $venue
     * @param Phoursquare_Service $service
     * @param
     */
    public function __construct(
        stdClass $data, 
        Phoursquare_Service $service,
        Phoursquare_Category $parentCategory = null
    ) {
        $this->_service = $service;

        if (!is_null($parentCategory)) {
            $this->_parent = $parentCategory;
        }

        if (property_exists($data, 'id')) {
            $this->_id = (int) $data->id;
        }

        if (property_exists($data, 'nodename')) {
            $this->_nodename = $data->nodename;
        }

        if (property_exists($data, 'fullpathname')) {
            $this->_fullpathname = $data->fullpathname;
        }

        if (property_exists($data, 'iconurl')) {
            $this->_iconurl = $data->iconurl;
        }

        if (property_exists($data, 'categories')) {
            $this->_categories = $data->categories;
        }
    }

    /**
     *
     * @return Phoursquare_Category
     */
    public function getParentCategory()
    {
        if (!$this->hasParentCategory()) {
            return null;
        }

        return $this->_parent;
    }

    /**
     *
     * @return boolean
     */
    public function hasParentCategory()
    {
        return!is_null($this->_parent);
    }

    /**
     *
     * @return string
     */
    public function getNodename()
    {
        return $this->_nodename;
    }

    /**
     *
     * @return string
     */
    public function getFullNodepath()
    {
        return $this->_fullpathname;
    }

    /**
     *
     * @return string
     */
    public function getIconUrl()
    {
        return $this->_iconurl;
    }

    /**
     *
     * @return Phoursquare_Service
     */
    public function getService()
    {
        return $this->_service;
    }

    /**
     *
     * @return integer
     */
    public function getid()
    {
        return $this->_id;
    }

    /**
     *
     * @return boolean
     */
    public function hasCategories()
    {
        return is_array($this->_categories) ||
        is_object($this->_categories);
    }

    /**
     *
     * @return Phoursquare_CategoriesList
     */
    public function getCategories()
    {
        if (!$this->hasCategories()) {
            return null;
        }

        if (!($this->_categories instanceof Phoursquare_CategoriesList)) {
            require_once 'Phoursquare/CategoriesList.php';
            $this->_categories = new Phoursquare_CategoriesList(
                            $this->_categories, $this->getService(), $this
            );
        }

        return $this->_categories;
    }

    /**
     *
     * @return Phoursquare_CategoriesList
     */
    public function getSiblings()
    {
        if(!$this->hasParentCategory()) {
            return new Phoursquare_CategoriesList(
                array(),
                $this->getService(),
                null
            );
        }

        $categories = $this->getParentCategory()
                           ->getCategories();
        
        $siblings   = clone $categories->filter($this->getId());
        $categories->clearFilter();

        return $siblings;
    }
    
}