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
 * Inspired by Doctrine2 AbstractCache
 * @see http://doctrine-project.org
 */

/**
 * Phoursquare_Cache_AbstractCache
 *
 * @category Cache
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
abstract class Phoursquare_Cache_AbstractCache {

    /**
     *
     * @var string
     */
    private $_namespace;

    /**
     *
     * @var boolean
     */
    private $_manageIds = false;

    /**
     *
     * @var boolean
     */
    private $_enableTags = false;

    /**
     *
     * @var boolean
     */
    private $_manageIdsNamespace = 'phoursquare_cache_ids';

    /**
     *
     * @var boolean
     */
    private $_tagIdsNamespace = 'phoursquare_tag_ids';

    /**
     *
     * @var boolean
     */
    private $_tagNamespace = 'tags_';


    /**
     *
     * @param string $namespace
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }

    /**
     *
     * @param string $id
     * @param string $data
     * @param integer $lifeTime
     * @param array $tags
     * @return boolean
     */
    public function save($id, $data, $lifeTime = false, $tags = null)
    {
        $id = $this->_getNamespacedId($id);
        if($this->_manageIds) {
            $this->_saveId($id);
        }

        // tag support
        if($this->_enableTags && is_array($tags) && !empty($tags)) {
            $tagId = $this->_tagNamespace . $id;
            $this->_saveTagId($tagId);

            $this->_doSave($tagId, $tags, $lifeTime);
        }


        return $this->_doSave($id, $data, $lifeTime);
    }

    /**
     *
     * @param string $id
     * @return boolean
     */
    public function delete($id)
    {
        $id = $this->_getNamespacedId($id);

        if ($this->_doDelete($id)) {
            if ($this->_manageIds) {
                $this->_deleteId($id);
            }
            if ($this->_enableTags) {
                $this->_deleteTagId($this->_tagNamespace . $id);
            }

            return true;
        }
        return false;
    }

    /**
     *
     * @param boolean $manageIds
     * @return void
     */
    public function setManageIds($manageIds = true)
    {
        $this->_manageIds = (bool)$manageIds;
    }

    /**
     *
     * @param boolean $enableTags
     * @return void
     */
    public function setEnableTags($enableTags = true)
    {
        $this->_enableTags = (bool)$enableTags;
    }

    /**
     *
     * @param string $id
     * @return string
     */
    public function fetch($id)
    {
        return $this->_doFetch($this->_getNamespacedId($id));
    }

    /**
     *
     * @param string $id
     * @return boolean
     */
    public function contains($id)
    {
        return $this->_doContains($this->_getNamespacedId($id));
    }

    /**
     *
     * @param string $id
     * @return string $id
     */
    private function _getNamespacedId($id)
    {
        if (!$this->_namespace ||
            strpos($id, $this->_namespace) === 0)
        {
            return $id;
        }

        return $this->_namespace . $id;
    }

    /**
     *
     * @param string $id
     * @return boolean
     */
    private function _saveId($id)
    {
        $ids = $this->getIds();
        $ids[] = $id;

        $cacheIdsIndexId = $this->_getNamespacedId($this->_manageIdsNamespace);
        return $this->_doSave($cacheIdsIndexId, array_unique($ids), null);
    }

    /**
     *
     * @param string $tagId
     * @return boolean
     */
    private function _saveTagId($tagId)
    {
        $tagIds = $this->getTagIds();
        $tagIds[] = $tagId;

        $tagIdsIndexId = $this->_getNamespacedId($this->_tagIdsNamespace);
        return $this->_doSave($tagIdsIndexId, array_unique($tagIds), null);
    }

    /**
     *
     * @param string $id
     * @return boolean
     */
    private function _deleteId($id)
    {
        $ids = $this->getIds();
        $key = array_search($id, $ids);
        if ($key !== false) {
            unset($ids[$key]);

            $cacheIdsIndexId = $this->_getNamespacedId($this->_manageIdsNamespace);
            return $this->_doSave($cacheIdsIndexId, $ids, null);
        }
        return false;
    }

    /**
     *
     * @param string $tagId
     * @return boolean
     */
    private function _deleteTagId($tagId)
    {
        $tagIds = $this->getTagIds();
        if(is_bool($tagIds)) {
            $tagIds = array();
        }

        $key = array_search($tagId, $tagIds);
        if ($key !== false) {
            unset($tagIds[$key]);

            $tagIdsIndexId = $this->_getNamespacedId($this->_tagIdsNamespace);
            return $this->_doSave($tagIdsIndexId, $tagIds, null);
        }
        return false;
    }

    /**
     *
     * @return array
     */
    public function getIds()
    {
        if(!$this->_manageIds) {
            throw new BadMethodCallException('Please set' . get_class($this) . '::setManageIds(true) to retrieve your own cached records');
        }

        return $this->fetch($this->_manageIdsNamespace);
    }

    /**
     *
     * @return array
     */
    public function getTagIds()
    {
        if(!$this->_enableTags) {
            throw new BadMethodCallException('Please set' . get_class($this) . '::setEnableTags(true) to retrieve your tag records');
        }

        return $this->fetch($this->_tagIdsNamespace);
    }

    /**
     *
     * @return array
     */
    public function deleteAll()
    {
        $ids = $this->getIds();
        if($ids == false) {
            $ids = array();
        }

        if($this->_enableTags) {
            $tagIds = $this->getTagIds();
            if($tagIds !== false) {
                $ids = array_merge($ids, $tagIds);
            }
        }

        foreach ($ids as $id) {
            $this->delete($id);
        }

        return $ids;
    }

    /**
     *
     * @return array
     */
    public function fetchAll()
    {
        $ids = $this->getIds();
        $stack = array();

        if($ids == false) {
            $ids = array();
        }

        foreach($ids as $id) {
            if(empty($id)) {
                continue;
            }
            $data = $this->_doFetch($id);
            if($data !== false) {
                $stack[$id] = $data;
            }
        }

        return $stack;
    }

    /**
     *
     * @param array|string $tags
     * @return array
     */
    public function fetchAllByTag($tags)
    {
        $ids = $this->_fetchIdsByTag($tags);
        $stack = array();

        foreach($ids as $id) {
            if(empty($id)) {
                continue;
            }
            $data = $this->_doFetch($id);
            if($data !== false) {
                $stack[$id] = $data;
            }
        }

        return $stack;
    }

    /**
     *
     * @param array|string $tags
     * @return array
     */
    public function deleteAllByTag($tags)
    {
        $ids = $this->_fetchIdsByTag($tags);
        $tagIds = $this->getTagIds();

        foreach ($ids as $id) {
            $this->delete($id);
        }

        return $ids;
    }

    /**
     *
     * @param array|string $tags
     * @return array
     */
    private function _fetchIdsByTag($tags)
    {
        $tagIds = $this->getTagIds();
        $stack = array();

        if($tagIds == false) {
            $tagIds = array();
        }

        if(!is_array($tags)) {
            $tags = array($tags);
        }

        foreach($tagIds as $tagId) {
            $data = $this->_doFetch($tagId);
            if(count(array_intersect($tags, $data)) == count($tags)) {
                $stack[] = substr($tagId, strlen($this->_tagNamespace));
            }
        }

        return $stack;
    }

    /**
     *
     * @param string $id
     * @return string
     */
    abstract protected function _doFetch($id);

    /**
     *
     * @param string $id
     * @return boolean
     */
    abstract protected function _doContains($id);

    /**
     *
     * @param string $id
     * @param string $data
     * @param int $lifeTime
     * @return boolean
     */
    abstract protected function _doSave($id, $data, $lifeTime = false, $tags = null);

    /**
     * @param string $id
     * @return boolean
     */
    abstract protected function _doDelete($id);

}