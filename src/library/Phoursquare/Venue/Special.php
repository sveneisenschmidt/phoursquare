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
 * Phoursquare_Venue_Special
 *
 * @category Venue
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Venue_Special
{

    /**
     *
     *
     * @var array
     */
    protected $_types = array(
        'mayor', 'count', 'frequency', 'other'
    );

    /**
     *
     * @var Phoursquare_Venue
     */
    protected $_venue;

    /**
     *
     * @var integer
     */
     protected $_id;

    /**
     *
     * @var string
     */
     protected $_message;

    /**
     *
     * @var boolean
     */
     protected $_mayor = false;

    /**
     *
     * @var boolean
     */
     protected $_count = false;

    /**
     *
     * @var boolean
     */
     protected $_frequency = false;

    /**
     *
     * @var boolean
     */
     protected $_other = false;

    /**
     *
     * @var boolean
     */
     protected $_kind = 'here';

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

        if(!property_exists($data, 'id')) {
            throw new Exception('Missing \'id\' poperty.');
        }

        if(property_exists($data, 'id')) {
            $this->_id = (int) $data->id;
        }

        if(property_exists($data, 'message')) {
            $this->_message = $data->message;
        }

        if(property_exists($data, 'type') &&
           in_array($data->type, $this->_types)
        ) {
            $this->{'_' . $data->type} = true;
        }

        if(property_exists($data, 'kind')) {
            $this->_kind = $data->kind;
            if($data->kind == 'nearby' && 
               property_exists($data, 'venue') &&
               property_exists($data->venue, 'id')
            ) {
                $this->_venue = (int)$id;
            }
        }
    }

    /**
     *
     * @return boolean
     */
    public function isMayorSpecial()
    {
        return $this->_mayor;
    }

    /**
     *
     * @return boolean
     */
    public function isFrequencySpecial()
    {
        return $this->_frequency;
    }

    /**
     *
     * @return boolean
     */
    public function isCountSpecial()
    {
        return $this->_count;
    }

    /**
     *
     * @return boolean
     */
    public function isOtherSpecial()
    {
        return $this->_other;
    }

    /**
     *
     * @return boolean
     */
    public function isNearbySpecial()
    {
        if($this->_kind == 'nearby') {
            return true;
        }

        return false;
    }

    /**
     *
     * @return boolean
     */
    public function isHereSpecial()
    {
        if($this->_kind == 'here') {
            return true;
        }

        return false;
    }

    /**
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
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
     * @return Phoursquare_Venue_Special
     */
    public function getRelatedVenue()
    {
        if(is_int($this->_venue) ||
           is_numeric($this->_venue)
        ) {
            return $this->getService()
                        ->getVenue($this->_venue);
        }

        return $this->_venue;
    }

    /**
     *
     * @return Phoursquare_Venue_SpecialsList
     */
    public function getOtherSpecials()
    {
        if(is_null($this->getRelatedVenue())) {
            return array();
        }

        $specials = $this->getRelatedVenue()
                         ->getSpecials();

        $siblings   = clone $specials->filter($this->getId());
        $specials->clearFilter();

        return $siblings;
    }
}