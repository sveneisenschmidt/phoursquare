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
 * @category Venue
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 */

/**
 * Phoursquare_Venue_Tip
 *
 * @category Venue
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Venue_Tip
{
    /**
     *
     * @var null|integer|Phoursquare_User_AbstractUser
     */
    protected $_user;

    /**
     *
     * @var string
     */
    protected $_text;

    /**
     *
     * @var string
     */
    protected $_created;

    /**
     *
     * @var integer
     */
     protected $_id;

    /**
     *
     * @var boolean
     */
     protected $_markAsToDo = false;

    /**
     *
     * @var boolean
     */
     protected $_markAsDone = false;

    /**
     *
     * @var boolean
     */
     protected $_unmark = false;

    /**
     *
     * @var Phoursquare_Venue
     */
     protected $_venue = null;
    
    /**
     *
     * @param stdClass $data
     * @param Phoursquare_Service $service
     * @param integer|Phoursquare_Venue $venue
     * @param
     */
    public function __construct(
        stdClass $data = null,
        Phoursquare_Service $service  = null,
        $venue  = null)
    {

        if(is_null($data)) {
            $data = new stdClass();
        }

        if(!is_null($venue)) {
            $this->_venue   = $venue;
        }

        if(!is_null($service)) {
            $this->_service   = $service;
        }

        if(property_exists($data, 'id')) {
            $this->_id = (int) $data->id;
        }

        if(property_exists($data, 'user') &&
           property_exists($data->user, 'id')
        ) {
            $this->_user = (int)$data->user->id;
        }

        if(property_exists($data, 'created')) {
            $this->_created= $data->created;
        }

        if(property_exists($data, 'text')) {
            $this->_text = $data->text;
        }
    }

    /**
     *
     * @return Phoursquare_User_AbstractUser
     */
    public function getCreator()
    {
        if(is_null($this->_user)) {
            return null;
        }

        if(!is_object($this->_user) &&
          (!$this->_user instanceof Phoursquare_User_AbstractUser)
        ) {
            $this->_user = $this->getService()->getUser(
                $this->_user
            );
        }

        return $this->_user;
    }

    /**
     *
     * @return string
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     *
     * @return Phoursquare_Venue_Tip
     */
    public function setText($text)
    {
        $this->_text = (string)$text;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->_created;
    }

    /**
     *
     * @return Phoursquare_Venue_TipsList
     */
    public function getAllTipsFromSameVenue()
    {
        return $this->getRelatedVenue()
                    ->getTips();
    }

    /**
     *
     * @return Phoursquare_Venue
     */
    public function getRelatedVenue()
    {
        if(is_int($this->_venue) || is_numeric($this->_venue)) {
            $this->_venue = $this->getService()
                                 ->getVenue($this->_venue);
        }
        return $this->_venue;
    }

    /**
     *
     * @return Phoursquare_Venue
     */
    public function setRelatedVenue($venue)
    {
        if(is_int($this->_venue) || is_numeric($this->_venue)) {
            $this->_venue = $this->getService()
                                 ->getVenue($this->_venue);
        }

        if(is_object($venue) && $venue instanceof Phoursquare_Venue) {
            $this->_venue = $venue;
        }

        return $this->_venue;
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
     * @todo implementation
     * @return boolean
     */
    public function markAsDone()
    {
        if(is_null($this->getId())) {
            throw new Exception('You can mark as done an unsaved Tip!');
        }

        return  $this->getService()
                     ->markAsDone($this);
    }

    /**
     *
     * @todo implementation
     * @return boolean
     */
    public function markAsToDo()
    {
        if(!is_null($this->_id)) {
            return  $this->getService()
                         ->markTipAsTodo($this);
        }

        $this->_markAsToDo = true;
        return true;
    }

    /**
     *
     * @return boolean
     */
    public function getMarkedAsToDo()
    {
        return $this->_markAsToDo;
    }

    /**
     *
     * @todo implementation
     * @return boolean
     */
    public function unMarkToDo()
    {
        if(is_null($this->getId())) {
            throw new Exception('You can unmark an unsaved Tip!');
        }

        return  $this->getService()
                     ->unMarkToDo($this);
    }
}