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
 * @category GeoLocation
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 */

/**
 * Phoursquare_GeoLocation
 *
 * @category GeoLocation
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_GeoLocation
{
    /**
     *
     * @var string
     */
    protected $_formattedAddress;
    /**
     *
     * @var string
     */
    protected $_latitude;
    /**
     *
     * @var string
     */
    protected $_longitude;

    /**
     *
     * @param string $address
     * @return Phoursquare_GeoLocation 
     */
    public function setFormattedAddress($address)
    {
        $this->_formattedAddress = $address;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getFormattedAddress()
    {
        return $this->_formattedAddress;
    }

    /**
     *
     * @param string $lat
     * @return Phoursquare_GeoLocation
     */
    public function setLatitude($lat)
    {
        $this->_latitude = $lat;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->_latitude;
    }

    /**
     *
     * @param string $lng
     * @return Phoursquare_GeoLocation
     */
    public function setLongitude($lng)
    {
        $this->_longitude = $lng;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->_longitude;
    }




}