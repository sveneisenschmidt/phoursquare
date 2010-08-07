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
 * @category Checkin
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses Phoursquare_Venue
 * @uses Phoursquare_User_AbstractUser
 */

/**
 * Phoursquare_Checkin
 *
 * @category Checkin
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Checkin
{

    /**
     *
     * @var Phoursquare_User_AbstractUser
     */
    protected $_user;
    
    /**
     *
     * @var integer
     */
    protected $_id;

    /**
     *
     * @var string
     */
    protected $_shout;

    /**
     *
     * @var string
     */
    protected $_created;

    /**
     *
     * @var string
     */
    protected $_timezone;

    /**
     *
     * @var string
     */
    protected $_display;

    /**
     *
     * @var Phoursquare_Venue
     */
    protected $_venue;

    /**
     *
     * @param stdClass $data
     */
    public function __construct(stdClass $data, Phoursquare_User_AbstractUser $user)
    {
        $this->_user = $user;

        if(!property_exists($data, 'id')) {
            throw new Exception('Missing \'id\' poperty.');
        }

        if(property_exists($data, 'id')) {
            $this->_id = (int) $data->id;
        }

        if(property_exists($data, 'created')) {
            $this->_created = $data->created;
        }

        if(property_exists($data, 'timezone')) {
            $this->_timezone = $data->timezone;
        }

        if(property_exists($data, 'display')) {
            $this->_display = $data->display;
        }

        if(property_exists($data, 'venue')) {
            $this->_venue = $data->venue->id;
        }

        if(property_exists($data, 'shout')) {
            $this->_shout = $data->shout;
        }
    }

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     *
     * @return string
     */
    public function getShout()
    {
        return $this->_shout;
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
     * @return string
     */
    public function getTimezone()
    {
        return $this->_timezone;
    }

    /**
     *
     * @return string
     */
    public function getDisplayMessage()
    {
        return $this->_display;
    }

    /**
     *
     * @return Phoursquare_User_AbstractUser
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     *
     * @return Phoursquare_Service
     */
    public function getService()
    {
        return $this->getUser()
                    ->getService();
    }

    /**
     *
     * @return boolean
     */
    public function hasVenue()
    {
        return (bool)$this->_venue;
    }

    /**
     *
     * @return Phoursquare_Venue
     */
    public function getVenue()
    {
        if(!$this->hasVenue()) {
            return null;
        }

        return $this->getUser()
                    ->getService()
                    ->getVenue($this->_venue)
                    ->setRelatedCheckin($this);
    }




}