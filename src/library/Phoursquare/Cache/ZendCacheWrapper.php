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
 * @category Cache
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses Phoursquare_Cache_AbstractCache
 */

require_once 'Phoursquare/Cache/AbstractCache.php';

/**
 * Phoursquare_Cache_ZendCacheWrapper
 *
 * @category Cache
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Cache_ZendCacheWrapper extends Phoursquare_Cache_AbstractCache
{
    /**
     * @var Zend_Cache_Core
     */
    protected $_zendCache;

    /**
     *
     * @param Zend_Cache_Core $zendCache
     */
    public function __construct(Zend_Cache_Core $zendCache)
    {
        $this->setZendCache($zendCache);
    }

    /**
     *
     * @param Zend_Cache_Core $zendCache
     * @return Phoursquare_Cache_ZendCacheWrapper
     */
    public function setZendCache(Zend_Cache_Core $zendCache)
    {
        $this->_zendCache = $zendCache;
        return $this;
    }

    /**
     *
     * @return Zend_Cache_Core
     */
    public function getZendCache()
    {
        return $this->_zendCache;
    }

    /**
     *
     * @param string $id
     * @return string
     */
    protected function _doFetch($id)
    {
        return $this->_zendCache->load($id);
    }

    /**
     *
     * @param string $id
     * @return boolean
     */
    protected function _doContains($id)
    {
        return (bool)$this->_zendCache->test($id);
    }

    /**
     *
     * @param string $id
     * @param string|array $data
     * @param integer $lifeTime
     * @return string
     */
    protected function _doSave($id, $data, $lifeTime = false, $tags = array())
    {
        return $this->_zendCache->save($data, $id, $tags, $lifeTime);
    }

    /**
     *
     * @param string $id
     * @return boolean
     */
    protected function _doDelete($id)
    {
        return $this->_zendCache->remove($id);
    }
}