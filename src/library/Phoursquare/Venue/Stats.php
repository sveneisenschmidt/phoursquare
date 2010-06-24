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
 * Phoursquare_Venue_Stats
 *
 * @category Venue
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Venue_Stats
{

    /**
     *
     * @var Phoursquare_Venue
     */
    protected $_venue;

    /**
     *
     * @var Phoursquare_Service
     */
    protected $_service;

    /**
     *
     * @var integer
     */
    protected $_checkins = 0;

    /**
     *
     * @var boolean
     */
    protected $_herenow = false;

    /**
     *
     * @var integer|Phoursquare_User_AbstractUser
     */
    protected $_mayor;
    
    /**
     *
     * @param stdClass $data
     * @param Phoursquare_Venue $venue
     * @param Phoursquare_Service $service
     * @param
     */
    public function __construct(
        stdClass $data,
        Phoursquare_Venue $venue,
        Phoursquare_Service $service)
    {
        $this->_venue   = $venue;
        $this->_service = $service;

        if(property_exists($data, 'checkins')) {
            $this->_checkins = $data->checkins;
        }

        if(property_exists($data, 'herenow')) {
            $this->_herenow = (bool) $data->herenow;
        }

        if(property_exists($data, 'mayor') &&
           property_exists($data->mayor, 'user') &&
           property_exists($data->mayor->user, 'id')
        ) {
            $this->_mayor = $data->mayor->user->id;
        }
    }

    /**
     *
     * @return integer
     */
    public function getCheckInCount()
    {
        return (int) $this->_checkins;
    }

    /**
     *
     * @return boolean
     */
    public function hereCheckedIn()
    {
        return (bool) $this->_herenow;
    }

    /**
     *
     * @return Phoursquare_Venue
     */
    public function getRelatedVenue()
    {
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
     * @return Phoursquare_User_AbstractUser
     */
    public function getMayor()
    {
        if(!$this->hasMayor()) {
            return null;
        }

        if(!is_object($this->_mayor) && 
          (!$this->_mayor instanceof Phoursquare_User_AbstractUser)
        ) {
            $this->_mayor = $this->getService()->getUser(
                $this->_mayor
            );
        }

        return $this->_mayor;
    }

    /**
     *
     * @return boolean
     */
    public function hasMayor()
    {
        if(is_null($this->_mayor)) {
            return false;
        }

        return true;
    }
}