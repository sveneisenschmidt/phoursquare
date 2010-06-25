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
 * @uses Phoursquare_AbstractResultSet
 * @uses Phoursquare_Category
 */

require_once 'Phoursquare/AbstractResultSet.php';
require_once 'Phoursquare/Category.php';

/**
 * Phoursquare_CategoriesList
 *
 * @category ResultSet
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_CategoriesList
    extends Phoursquare_AbstractResultSet
        implements RecursiveIterator
{
    /**
     *
     * @var Phoursquare_Category
     */
    protected $_parent;

    /**
     *
     * @param array $data
     * @param Phoursquare_Service $service
     */
    public function __construct(
        array $data,
        Phoursquare_Service $service,
        Phoursquare_Category $parentCategory = null
    ) {
        parent:: __construct($data, $service);

        if(!is_null($parentCategory)) {
            $this->_parent = $parentCategory;
        }
    }

    /**
     *
     * @return Phoursquare_Category
     */
    public function getParentCategory()
    {
        if(!$this->hasParentCategory()) {
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
        return !is_null($this->_parent);
    }

    /**
     *
     * @return Phoursquare_Category
     */
    protected function _parse($key)
    { 
        return new Phoursquare_Category(
            $this->_data[$key],
            $this->getService(),
            $this->getParentCategory()
        );
    }

    /**
     *
     * @return Phoursquare_Category
     */
    public function  current()
    {
        return parent::current();
    }

    /**
     *
     * @return Phoursquare_Category
     */
    public function getFirstInList()
    {
        return parent::getFirstInList();
    }

    /**
     *
     * @return Phoursquare_Category
     */
    public function getLastInList()
    {
        return parent::getLastInList();
    }

    /**
     *
     * @return Phoursquare_Category
     */
    public function find($id)
    {

        $search = new RecursiveIteratorIterator($this,
                        RecursiveIteratorIterator::LEAVES_ONLY);

        foreach($search as $category) {
            if($category->getId() == $id) {
                return $category;
            }
        }

        return null;
    }

    /**
     *
     * @return Phoursquare_CategoriesList
     */
    public function getChildren()
    {
        return $this->current()
                    ->getCategories();
    }

    /**
     *
     * @return boolean
     */
    public function  hasChildren()
    {
        return $this->current()
                    ->hasCategories();
    }

}