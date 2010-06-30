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
 * @uses Phourquare_Query
 * @uses Phourquare_Search_VenueList
 * @uses Phourquare_Search_TipsList
 * @uses Phourquare_Search_NonFriendsList
 */

require_once 'Phoursquare/Service.php';
require_once 'Phoursquare/Query.php';

/**
 * Phoursquare_Search
 *
 * @category Search
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Search
{
    /**
     *
     * @var Phoursquare_Service
     */
    protected $_service;

    /**
     *
     * @param Phoursquare_Service $service
     */
    public function __construct(Phoursquare_Service $service = null)
    {
        if(!is_null($service)) {
            $this->setService($service);
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
     * @param Phoursquare_Service $service
     * @return Phoursquare_Search
     */
    public function setService(Phoursquare_Service $service)
    {
        $this->_service = $service;
        return $this;
    }

    /**
     *
     * @return Phoursquare_Query
     */
    public function getQuery()
    {
        return new Phoursquare_Query();
    }

    /**
     *
     * @param string $type
     * @return Phoursquare_Query
     */
    public function query($type)
    {
        return $this->getQuery()
                    ->setSearch($this)
                    ->setType($type);
    }

    /**
     *
     * @return Phoursquare_Query
     */
    public function venue()
    {
        return $this->query(Phoursquare_Query::VENUE);
    }

    /**
     *
     * @return Phoursquare_Query
     */
    public function tip()
    {
        return $this->query(Phoursquare_Query::TIP);
    }

    /**
     *
     * @return Phoursquare_Query
     */
    public function nonFriendByName()
    {
        return $this->query(Phoursquare_Query::NON_FRIEND_NAME);
    }

    /**
     *
     * @return Phoursquare_Query
     */
    public function friendByName()
    {
        return $this->query(Phoursquare_Query::FRIEND_NAME);
    }

    /**
     *
     * @return Phoursquare_Query
     */
    public function nonFriendByPhone()
    {
        return $this->query(Phoursquare_Query::NON_FRIEND_PHONE);
    }

    /**
     *
     * @return Phoursquare_Query
     */
    public function nonFriendByTwitter()
    {
        return $this->query(Phoursquare_Query::NON_FRIEND_TWITTER);
    }

    /**
     *
     * @param Phoursquare_Query $query
     * @return Phoursquare_AbstractResultSet
     */
    public function find(Phoursquare_Query $query)
    {
        $query->detach();
        $query->validate($this);
        


        switch ($query->getType()) {

            case Phoursquare_Query::VENUE:
            case Phoursquare_Query::TIP:
            case Phoursquare_Query::NON_FRIEND_NAME:
            case Phoursquare_Query::NON_FRIEND_PHONE:
            case Phoursquare_Query::NON_FRIEND_TWITTER:

                $data = $this->getService()
                             ->getRequest()
                             ->fetchUrl(
                                 $query->getUri(),
                                 $query->getParameters()
                             );

                case Phoursquare_Query::VENUE:
                    return $this->_assembleVenues($data, $query);
                break;

                case Phoursquare_Query::TIP:
                    return $this->_assembleTips($data, $query);
                break;

                case Phoursquare_Query::NON_FRIEND_NAME:
                case Phoursquare_Query::NON_FRIEND_PHONE:
                case Phoursquare_Query::NON_FRIEND_TWITTER:
                    return $this->_assembleNonFriends($data, $query);
                break;
            break;

            case Phoursquare_Query::FRIEND_NAME:
            case Phoursquare_Query::FRIEND_PHONE:
            case Phoursquare_Query::FRIEND_TWITTER:
                 return $this->_assembleFriends($query);
            break;

        }

        throw new Exception('Not yet implemented type: ' . $query->getType());
    }

    /**
     *
     * @param stdClass $data
     * @param Phoursquare_Query $query
     * @return Phoursquare_Search_VenueList
     */
    protected function _assembleVenues(stdClass $data, Phoursquare_Query $query)
    {
        $key = (int)!$query->getNearby();

        if(!property_exists($data, 'groups')) {
            throw new Exception('Invalid response structure');
        }

        if(!isset($data->groups[$key])) {
            return new ArrayObject(array());
        }

        if(!property_exists($data->groups[$key], 'venues') ) {
            throw new Exception('Invalid response structure');
        }

        require_once 'Phoursquare/Search/VenueList.php';
        return new Phoursquare_Search_VenueList(
            $data->groups[$key]->venues, $this->getService()
        );
    }

    /**
     *
     * @param stdClass $data
     * @param Phoursquare_Query $query
     * @return Phoursquare_Search_TipsList
     */
    protected function _assembleTips(stdClass $data, Phoursquare_Query $query)
    {
        $key = (int)!$query->getNearby();

        if(!property_exists($data, 'groups')) {
            throw new Exception('Invalid response structure');
        }

        if(!isset($data->groups[$key])) {
            return new ArrayObject(array());
        }

        if(!property_exists($data->groups[$key], 'tips') ) {
            throw new Exception('Invalid response structure');
        }

        require_once 'Phoursquare/Search/TipsList.php';
        return new Phoursquare_Search_TipsList(
            $data->groups[$key]->tips, $this->getService(), null
        );
    }

    /**
     *
     * @param stdClass $data
     * @param Phoursquare_Query $query
     * @return Phoursquare_Search_NonFriendsList
     */
    protected function _assembleNonFriends(stdClass $data, Phoursquare_Query $query)
    {
        if(!property_exists($data, 'users')) {
            throw new Exception('Invalid response structure');
        }

        require_once 'Phoursquare/Search/NonFriendsList.php';
        $iterator = new Phoursquare_Search_NonFriendsList(
            $data->users, $this->getService()
        );
        if(!is_null($query->getLimit())) {
            $iterator->shorten($query->getLimit());
        }
        return $iterator;
    }

    /**
     *
     * @param Phoursquare_Query $query
     * @return Phoursquare_UsersList
     */
    protected function _assembleFriends(Phoursquare_Query $query)
    {
        $friends = $this->getService()
                        ->getAuthenticatedUser()
                        ->getFriends();

        if(is_null($query->getKeyword()) ||
           trim($query->getKeyword()) == ''
        ) {
            return $friends;
        }


        // filtering

        foreach($friends as $key => $friend) {

            switch ($query->getType()) {

                case Phoursquare_Query::FRIEND_NAME:
                    $name = strtolower(' ' . $friend->getFirstname() . 
                                       ' ' . $friend->getLastname());

                    if(strpos($name, $query->getKeyword()) === false) {
                        $friends->remove($key);
                    }
                break;

                case Phoursquare_Query::FRIEND_PHONE:
                    $phone = trim($query->getKeyword());
                    $phone = ltrim($phone, '0');
                    $phone = str_replace(array('/', ',', '-', '.'), '', $phone);
                    
                    if($phone != $friend->getPhone() ||
                       is_null($friend->getPhone())
                    ) {
                        $friends->remove($key);
                    }
                break;

                case Phoursquare_Query::FRIEND_TWITTER:
                    $nick = trim($query->getKeyword());
                    $nick = ltrim($nick, '@');

                    if(!$friend->hasTwitter() ||
                       $nick != $friend->getTwitter()
                    ) {
                        $friends->remove($key);
                    }

                break;

                default:
                    return array();

            }
        }

        if(!is_null($query->getLimit())) {
            $friends->shorten($query->getLimit());
        }
        
        return $friends->rebase()
                       ->rewind();
    }

    /**
     *
     * @param string $name
     * @param array $arguments
     * @return Phoursquare_Search
     */
    public function  __call($name, $arguments)
    {
        switch ($name) {

            case 'limit':
            case 'geolat':
            case 'geolong':
            case 'keyword':

                throw new Exception('Please set/load first a Query via: ' .
                                    '::venue(),::tip() or ::query($type)');
            break;


            default:
                throw new Exception('Call to undefined method' .
                                    'Phoursquare_Search::' . $name . '()');
        }
    }

}
