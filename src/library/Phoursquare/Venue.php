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
 * @uses Phoursquare_Venue_Stats
 * @uses Phoursquare_Venue_TipsList
 * @uses Phoursquare_Venue_SpecialsList
 * @uses Phoursquare_Venue_CategoriesList
 */

/**
 * Phoursquare_Venue
 *
 * @category Venue
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Venue
{
    /**
     *
     * @var Phoursquare_Checkin
     */
    protected $_relatedCheckin;

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
    protected $_name;

    /**
     *
     * @var string
     */
    protected $_address;

    /**
     *
     * @var string
     */
    protected $_city;

    /**
     *
     * @var string
     */
    protected $_state;

    /**
     *
     * @var string
     */
    protected $_zip;

    /**
     *
     * @var string
     */
    protected $_geolat;

    /**
     *
     * @var string
     */
    protected $_geolong;

    /**
     *
     * @var stdClass|Phoursquare_Venue_Stats
     */
    protected $_stats;

    /**
     *
     * @var stdClass|Phoursquare_Venue_TipsList
     */
    protected $_tips;

    /**
     *
     * @var stdClass|Phoursquare_Venue_CategoriesList
     */
    protected $_categories;

    /**
     *
     * @var stdClass|Phoursquare_Venue_SpecialsList
     */
    protected $_specials;

    /**
     *
     * @param stdClass $data
     * @param Phoursquare_Service $service
     */
    public function __construct(stdClass $data, Phoursquare_Service $service)
    {
        $this->_service = $service;

        if(!property_exists($data, 'id')) {
            throw new Exception('Missing \'id\' poperty.');
        }

        if(property_exists($data, 'id')) {
            $this->_id = (int) $data->id;
        }

        if(property_exists($data, 'name')) {
            $this->_name = $data->name;
        }

        if(property_exists($data, 'address')) {
            $this->_address = $data->address;
        }

        if(property_exists($data, 'city')) {
            $this->_city = $data->city;
        }

        if(property_exists($data, 'state')) {
            $this->_state = $data->state;
        }

        if(property_exists($data, 'zip')) {
            $this->_zip = $data->zip;
        }

        if(property_exists($data, 'geolat')) {
            $this->_geolat = $data->geolat;
        }

        if(property_exists($data, 'geolong')) {
            $this->_geolong = $data->geolong;
        }

        if(property_exists($data, 'stats')) {
            $this->_stats = $data->stats;
        }

        if(property_exists($data, 'tips')) {
            $this->_tips = $data->tips;
        }

        if(property_exists($data, 'specials')) {
            $this->_specials = $data->specials;
        }

        if(property_exists($data, 'categories')) {
            $this->_categories = array();
            foreach($data->categories as $category) {
                array_push($this->_categories, $category->id);
            }
        }
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
     * @param Phoursquare_Service $checkin
     */
    public function setRelatedCheckin($checkin)
    {
        $this->_relatedCheckin = $checkin;
        return $this;
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
    public function getName()
    {
        return $this->_name;
    }

    /**
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->_address;
    }

    /**
     *
     * @return string
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->_zip;
    }

    /**
     *
     * @return string
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     *
     * @return string
     */
    public function getGeoLantide()
    {
        return $this->_geolat;
    }

    /**
     *
     * @return string
     */
    public function getGeoLongitude()
    {
        return $this->_geolong;
    }

    /**
     *
     * @return boolean
     */
    public function hasTips()
    {
        return !is_null($this->_tips);
    }

    /**
     *
     * @return Phoursquare_Venue_TipsList
     */
    public function getTips()
    {
        if(!$this->hasTips()) {
            return null;
        }

        if(!($this->_tips instanceof Phoursquare_Venue_TipsList)) {
            require_once 'Phoursquare/Venue/TipsList.php';
            $this->_tips = new  Phoursquare_Venue_TipsList(
                $this->_tips, $this->getService(), $this
            );
        }

        return $this->_tips;
    }

    /**
     *
     * @param Phoursquare_Venue_Tip $tip
     * @return Phoursquare_Venue_Tip
     */
    public function addTip(Phoursquare_Venue_Tip $tip)
    {
        if(!is_null($tip->getId())) {
            throw new Exception('$tip does already exist!');
        }

        return $this->getService()
                    ->addTip($tip, $this);
    }

    /**
     *
     * @return boolean
     */
    public function hasSpecials()
    {
        return !is_null($this->_specials);
    }

    /**
     *
     * @return Phoursquare_Venue_SpecialsList
     */
    public function getSpecials()
    {
        if(!$this->hasSpecials()) {
            return null;
        }

        if(!($this->_specials instanceof Phoursquare_Venue_SpecialsList)) {
            require_once 'Phoursquare/Venue/SpecialsList.php';
            $this->_specials = new  Phoursquare_Venue_SpecialsList(
                $this->_specials, $this, $this->getService()
            );
        }

        return $this->_specials;
    }

    /**
     *
     * @return boolean
     */
    public function hasCatgories()
    {
        return !is_null($this->_categories);
    }

    /**
     *
     * @return Phoursquare_Venue_CategoriesList
     */
    public function getCategories()
    {
        if(!$this->hasCatgories()) {
            return null;
        }

        if(!($this->_categories instanceof Phoursquare_Venue_CategoriesList)) {
            require_once 'Phoursquare/Venue/CategoriesList.php';
            $this->_categories = new Phoursquare_Venue_CategoriesList(
                $this->_categories,
                $this->getService(),
                $this
            );
        }

        return $this->_categories;
    }

    /**
     *
     * @return Phoursquare_CheckinList
     */
    public function getCheckins($limit = 25)
    {
        throw new Exception('Not yet implemented. ' .
                            'Foursquare API seems to missing thi spart');
    }

    /**
     *
     * @param array $options
     * @return Phoursquare_Checkin
     */
    public function checkinHere(array $options = array())
    {
        return $this->getService()
                    ->doCheckin($this->getId(), $options);
    }

    /**
     *
     * @return boolean
     */
    public function hasStats()
    {
        return !is_null($this->_stats);
    }

    /**
     *
     * @return Phoursquare_Venue_Stats
     */
    public function getStats()
    {
        if(!$this->hasStats()) {
            return null;
        }

        if(!($this->_stats instanceof Phoursquare_Venue_Stats)) {
            require_once 'Phoursquare/Venue/Stats.php';
            $this->_stats = new Phoursquare_Venue_Stats(
                $this->_stats,
                $this,
                $this->getService()
            );
        }

        return $this->_stats;
    }

    /**
     *
     * @return boolean
     */
    public function hasMayor()
    {
        return $this->getStats()
                    ->hasMayor();
    }

    /**
     *
     * @return Phoursquare_User_AbstractUser
     */
    public function getMayor()
    {
        return $this->getStats()
                    ->getMayor();
    }

    /**
     *
     * @todo implementation
     * @return boolean
     */
    public function flagAsMislocated()
    {
        
    }

    /**
     *
     * @todo implementation
     * @return boolean
     */
    public function flagAsDuplicated()
    {

    }
    
    /**
     *
     * @todo implementation
     * @return boolean
     */
    public function flagAsClosed()
    {

    }

}