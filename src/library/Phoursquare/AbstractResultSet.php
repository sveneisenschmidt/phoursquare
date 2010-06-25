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
 */

/**
 * Phoursquare_AbstractResultSet
 *
 * @category ResultSet
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
abstract class Phoursquare_AbstractResultSet implements Countable, Iterator
{
    /**
     *
     * @var Phoursquare_Service
     */
    protected $_service;

    /**
     *
     * @var array
     */
    protected $_data;

    /**
     *
     * @var array
     */
    protected $_filteredIds = array();

    /**
     *
     * @var integer
     */
    protected $_key = 0;

    /**
     *
     * @param array $data
     * @param Phoursquare_Service $service
     */
    public function __construct(array $data, Phoursquare_Service $service)
    {
        $this->_data  = $data;
        $this->_service = $service;
    }

    /**
     *
     * @return mixed
     */
    public function current()
    {
        return $this->_parse($this->_key);
    }

    /**
     *
     * @param integer $key
     * @return mixed
     */
    abstract protected function _parse($key);

    /**
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_data);
    }

    /**
     *
     * @return integer
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     *
     * @return void
     */
    public function next()
    {
        $this->_key++;
    }

    /**
     *
     * @return void
     */
    public function rewind()
    {
        $this->_key = 0;
    }

    /**
     *
     * @return boolean
     */
    public function valid()
    {
        if(!isset($this->_data[$this->_key])) {
            return false;
        }

        $id = $this->_data[$this->_key];
        if(in_array($id, $this->_filteredIds)) {
            return false;
        }

        return true;
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
     * @return mixed
     */
    public function getFirstInList()
    {
        return $this->_parse(0);
    }

    /**
     *
     * @return mixed
     */
    public function getLastInList()
    {
        $key = (($this->count() -1) < 0)
                   ? 0 : $this->count() -1;
        
        return $this->_parse($key);
    }

    /**
     *
     * @param integer $id
     * @return Phoursquare_AbstractResultSet
     */
    public function filter($id)
    {
        array_push($this->_filteredIds, $id);
        return $this;
    }

    /**
     *
     * @return Phoursquare_AbstractResultSet
     */
    public function clearFilter()
    {
        $this->_filteredIds = array();
        return $this;
    }


}