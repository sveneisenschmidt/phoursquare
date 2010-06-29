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
 * @category Search
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses Phourquare_Service
 */

require_once 'Phoursquare/Search.php';

/**
 * Phoursquare_Query
 *
 * @category Search
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Query implements IteratorAggregate
{
    /**
     *
     * @var const
     */
    const VENUE = 'venue';

    /**
     *
     * @var const
     */
    const TIP = 'tip';

    /**
     *
     * @var const
     */
    const NON_FRIEND_NAME = 'non-friend-name';

    /**
     *
     * @var const
     */
    const NON_FRIEND_PHONE= 'non-friend-phone';

    /**
     *
     * @var const
     */
    const NON_FRIEND_TWITTER = 'non-friend-twitter';

    /**
     *
     * @var const
     */
    const FRIEND_NAME = 'friend-name';

    /**
     *
     * @var const
     */
    const FRIEND_PHONE= 'friend-phone';

    /**
     *
     * @var const
     */
    const FRIEND_TWITTER = 'friend-twitter';

    /**
     *
     * @var array
     */
    protected $_types = array(
        self::VENUE,
        self::TIP,
        self::FRIEND_NAME,
        self::FRIEND_PHONE,
        self::FRIEND_TWITTER,
        self::NON_FRIEND_NAME,
        self::NON_FRIEND_PHONE,
        self::NON_FRIEND_TWITTER
    );

    /**
     *
     * @var array
     */
    protected $_uris = array(
        self::VENUE                 => '/v1/venues.json',
        self::TIP                   => '/v1/tips.json',
        self::NON_FRIEND_NAME       => '/v1/findfriends/byname.json',
        self::NON_FRIEND_PHONE      => '/v1/findfriends/byphone.json',
        self::NON_FRIEND_TWITTER    => '/v1/findfriends/bytwitter.json',
        self::FRIEND_NAME       => null,
        self::FRIEND_PHONE      => null,
        self::FRIEND_TWITTER    => null
    );

    /**
     *
     * @var Phoursquare_Search
     */
    protected $_search;

    /**
     *
     * @var string
     */
    protected $_type;

    /**
     *
     * @var integer
     */
    protected $_limit;

    /**
     *
     * @var string
     */
    protected $_term;

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
     * @var boolean
     */
    protected $_detached = true;

    /**
     *
     * @var boolean
     */
    protected $_nearby = true;

    /**
     *
     * @var array
     */
    protected $_address = array();

    /**
     *
     * @param Phoursquare_Search $search
     */
    public function __construct(Phoursquare_Service $search = null)
    {
        if(!is_null($search)) {
            $this->setSearch($search);
        }
    }

    /**
     *
     * @return Phoursquare_Search
     */
    public function getSearch()
    {
        return $this->_search;
    }

    /**
     *
     * @param Phoursquare_Search $search
     * @return Phoursquare_Search
     */
    public function setSearch(Phoursquare_Search $search)
    {
        $this->_detached = false;
        $this->_search   = $search;
        return $this;
    }

    /**
     *
     * @param string $type
     * @return Phoursquare_Query
     */
    public function setType($type)
    {
        if(!in_array($type, $this->_types)) {
            throw new Exception('$type is not supported');
        }
        
        $this->_type = $type;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     *
     * @param integer $limit
     * @return Phoursquare_Query
     */
    public function limit($limit)
    {
        $this->_limit = (int) $limit;
        return $this;
    }

    /**
     *
     * @return void
     */
    protected function _onlyTipAndVenue()
    {
        if($this->_type !== self::VENUE &&
           $this->_type !== self::TIP
        ) {
            throw new Exception('Setting is only supported when ' .
                                'searching for venues or tips');
        }
    }

    /**
     *
     * @param string $term
     * @return Phoursquare_Query
     */
    public function keyword($term)
    {
        $this->_term = strtolower($term);
        return $this;
    }

    /**
     *
     * @param string $geolat
     * @return Phoursquare_Query
     */
    public function geolat($geolat)
    {
        if(!is_string($geolat) && !is_numeric($geolat)) {
            throw new InvalidArgumentException('No String given-in!');
        }

        $this->_onlyTipAndVenue();
        $this->_geolat = $geolat;
        return $this;
    }

    /**
     *
     * @param string $geolong
     * @return Phoursquare_Query
     */
    public function geolong($geolong)
    {
        if(!is_string($geolong) && !is_numeric($geolong)) {
            throw new InvalidArgumentException('No String given-in!');
        }
        
        $this->_onlyTipAndVenue();
        $this->_geolong = $geolong;
        return $this;
    }

    /**
     *
     * @param boolean $nearby
     * @return Phoursquare_Query
     */
    public function nearby($nearby = true)
    {
        $this->_nearby = (bool)$nearby;
        return $this;
    }

    /**
     *
     * @param boolean $me
     * @return Phoursquare_Query
     */
    public function me($me = true)
    {
        $this->_nearby = !(bool)$me;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getNearby()
    {
        return $this->_nearby;
    }

    /**
     *
     * @return integer
     */
    public function getLimit()
    {
        return (int)$this->_limit;
    }

    /**
     *
     * @return string
     */
    public function getKeyword()
    {
        return (string)$this->_term;
    }

    /**
     *
     * @param boolean $me
     * @return Phoursquare_Query
     */
    public function address($address = array())
    {
        if(is_string($address)) {
            $address = array($address);
        }

        $this->_address = $address;
        return $this;
    }

    /**
     *
     * @return Phoursquare_AbstractResultSet
     */
    public function getIterator()
    {
        if(is_null($this->getSearch()) || 
           !($this->getSearch() instanceof Phoursquare_Search)
        ) {
            throw new Exception('To search directly via a query, please ' .
                                'set first a search instance via setSearch');
        }

        return $this->getSearch()
                    ->find($this);
    }

    /**
     *
     * @return array
     */
    public function getParameters()
    {
        $parameters = array();

        if($this->getType() == self::VENUE ||
           $this->getType() == self::TIP
        ) {
        if(!is_null($this->_limit)) {
            $parameters['l'] = $this->_limit;
        }
        }


        if(!is_null($this->_term)) {
            $parameters['q'] = $this->_term;
        }
        if(!is_null($this->_geolat)) {
            $parameters['geolat'] = $this->_geolat;
        }
        if(!is_null($this->_geolong)) {
            $parameters['geolong'] = $this->_geolong;
        }

        return $parameters;
    }

    /**
     *
     * @return string
     */
    public function getUri()
    {
        return $this->_uris[
            $this->_type
        ];
    }

    /**
     *
     * @return Phoursquare_Query
     */
    public function detach()
    {
        $this->_detached = true;
        $this->_search   = null;
        return $this;
    }

    /**
     *
     * @param  Phoursquare_Search $search
     * @return Phoursquare_Query
     */
    public function validate(Phoursquare_Search $search)
    {
        switch($this->_type) {

            case self::VENUE:
            case self::TIP:

                if(!empty($this->_address)) {
                    $addresses = $search->getService()
                                        ->geocode($this->_address);

                    $address = array_shift($addresses);

                    $this->_geolat  = $address->getLatitude();
                    $this->_geolong = $address->getLongitude();
                }

                if(is_null($this->_geolat)  || empty($this->_geolat) ||
                   is_null($this->_geolong) || empty($this->_geolong)
                ) {
                    throw new Exception('Geolat & Geolong are required!');
                }


            break;
        }
    }
}
