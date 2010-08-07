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
 * @category Service
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses Phourquare_Request
 * @uses Phourquare_Search
 * @uses Phoursquare_GeoLocation
 * @uses Phourquare_Cache_AbstractCache
 * @uses Phourquare_Cache_FriendRequestsList
 * @uses Phoursquare_User_Friend
 * @uses Phoursquare_User_AuthenticatedUser
 * @uses Phoursquare_User_NonRelatedUser
 * @uses Phoursquare_Venue_Tip
 */

require_once 'Phoursquare/Request.php';

/**
 * Phourquare_Service
 *
 * @category Service
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
abstract class Phoursquare_Service
{
    /**
     *
     * @var Phoursquare_Request
     */
    protected $_request;
    
    /**
     *
     * @var Phoursquare_Auth_Http
     */
    protected $_auth;

    /**
     *
     * @return Phoursquare_Request
     */
    public function getRequest()
    {
        if(is_null($this->_request)) {
            $this->_request = new Phoursquare_Request();
        }

        return $this->_request;
    }

    /**
     *
     * @param Zend_Cache_Core| $cache
     * @return Phoursquare_Service
     */
    public function setCache($cache)
    {
        $this->getRequest()
             ->setCache($cache);

        return $this;
    }

    /**
     *
     * @return Phoursquare_Cache_AbstractCache
     */
    public function getCache()
    {
        if(!$this->hasCache()) {
            return null;
        }

        return $this->getRequest()
                    ->getCache();
    }

    /**
     *
     * @return boolean
     */
    public function hasCache()
    {
        return $this->getRequest()
                    ->hasCache();
    }

    /**
     *
     * @param Phoursquare_Request $request
     * @return Phoursquare_Service
     */
    public function setAuth(Phoursquare_Auth_Http $auth)
    {
        $this->getRequest()
             ->setAuth($auth);
        
        return $this;
    }

    /**
     *
     * @return Phoursquare_Auth_Http
     */
    public function getAuth()
    {
        return $this->getRequest()
                    ->getAuth();
    }

    /**
     *
     * @return boolean
     */
    public function hasAuth()
    {
        return $this->getRequest()
                    ->hasAuth();
    }

    /**
     *
     * @return Phoursquare_User_AuthenticatedUser
     */
    public function  getAuthenticatedUser()
    {
        $hash = null;
        if($this->getAuth() instanceof Phoursquare_Auth_Http) {
            $hash = $this->getAuth()
                         ->getUsername();
        }
        
        return $this->_getUser(null, sha1($hash));
    }

    /**
     *
     * @return Phoursquare_User_AbstractUser
     */
    public function getUser($uid)
    {
        return $this->_getUser($uid, sha1($uid));
    }

    /**
     *
     * @param integer $venueId
     * @return Phoursquare_Venue
     */
    public function getVenue($venueId)
    {
        $data = $this->getRequest()
                     ->fetchVenue($venueId);

        if(!property_exists($data, 'venue')) {
            throw new Exception('No valid venue response returned.');
        }

        require_once 'Phoursquare/Venue.php';
        return new Phoursquare_Venue(
            $data->venue, $this
        );
    }

    /**
     *
     * @param integer $fromUserId
     * @return Phoursquare_UsersList
     */
    public function getFriends($fromUserId = null)
    {
        $data = $this->getRequest()
                     ->fetchFriends($fromUserId);

        if(!property_exists($data, 'friends')) {
            throw new Exception('No valid friends response returned.');
        }

        require_once 'Phoursquare/UsersList.php';
        return new Phoursquare_UsersList(
            $data->friends, $this
        );
    }

    /**
     *
     * @return Phoursquare_CategoriesList
     */
    public function getCategories()
    {
        $data = $this->getRequest()
                     ->fetchCategories();

        if(!property_exists($data, 'categories')) {
            throw new Exception('No valid categories response returned.');
        }

        require_once 'Phoursquare/CategoriesList.php';
        return new Phoursquare_CategoriesList(
            $data->categories, $this
        );
    }

    /**
     *
     * @param integer $id
     * @return Phoursquare_Category
     */
    public function getCategory($id)
    {
        return $this->getCategories()
                    ->find((int) $id);
    }

    /**
     *
     * @param integer $limit
     * @param integer $sinceId
     * @return Phoursquare_CheckinList
     */
    public function getAuthenticatedUserCheckins($limit = 25, $sinceId = null)
    {
        $data = $this->getRequest()
                     ->fetchHistory($limit, $sinceId);

        if(!property_exists($data, 'checkins')) {
            throw new Exception('No valid checkin response returned.');
        }

        require_once 'Phoursquare/CheckinList.php';
        return new Phoursquare_CheckinList(
            $data->checkins, 
            $this,
            $this->getAuthenticatedUser()
        );
    }

    /**
     *
     * @param integer $uid
     * @return Phoursquare_User_AbstractUser
     */
    protected function _getUser($uid = null, $hash = null)
    {
        $data = $this->getRequest()
                     ->fetchUser($uid, $hash);

        return $this->parseUser($data);
    }

    /**
     *
     * @param stdClass $data
     * @return Phoursquare_User_AbstractUser
     */
    public function parseUser(stdClass $data)
    {
        if(!property_exists($data, 'user')) {
            throw new Exception('No valid user response returned.');
        }
        
        if(property_exists($data->user, 'status')  &&
           property_exists($data->user, 'id') &&
           property_exists($data->user, 'settings')
        ) {
            require_once 'Phoursquare/User/AuthenticatedUser.php';
            return new Phoursquare_User_AuthenticatedUser($data->user, $this);
        }

        if(property_exists($data->user, 'friendstatus') &&
           $data->user->friendstatus == 'friend'
        ) {
            require_once 'Phoursquare/User/Friend.php';
            return new Phoursquare_User_Friend($data->user, $this);
        }

        if(property_exists($data->user, 'id')) {
            require_once 'Phoursquare/User/NonRelatedUser.php';
            return new Phoursquare_User_NonRelatedUser($data->user, $this);
        }

        throw new Exception('No valid user class could be detected.');
    }

    /**
     *
     * @return Phoursquare_Search
     */
    public function getSearch()
    {
        require_once 'Phoursquare/Search.php';
        return new Phoursquare_Search($this);
    }

    /**
     *
     * @return array of Phoursquare_GeoLocation
     */
    public function geocode(array $parts)
    {
        $data = $this->getRequest()
                     ->resolveAddress($parts);

        if(empty($data->results) ||
           !isset($data->results[0])
        ) {
            throw new Exception('Address not found!');
        }

        $stack = array();
        require_once 'Phoursquare/GeoLocation.php';

        foreach($data->results as $data) {

            $geoLocation = new Phoursquare_GeoLocation();
            if(property_exists($data, 'formatted_address')) {
                $geoLocation->setFormattedAddress($data->formatted_address);
            }

            if(property_exists($data, 'geometry') &&
               property_exists($data->geometry, 'location')
            ) {
                $location = $data->geometry->location;
                if(property_exists($location, 'lat')) {
                    $geoLocation->setLatitude($location->lat);
                }
                if(property_exists($location, 'lng')) {
                    $geoLocation->setLongitude($location->lng);
                }
            }
            array_push($stack, $geoLocation);
        }
        
        return $stack;
    }

    /**
     *
     * @return Phoursquare_Search
     */
    public function search()
    {
        return $this->getSearch();
    }

    /**
     *
     * @param integer|Phoursquare_Venue
     * @param array $options
     * @return Phoursquare_Checkin
     */
    public function doCheckin($venue, array $options = array())
    {
        if(!is_int($venue) &&
           !is_numeric($venue) &&
           !is_object($venue) &&
           !($venue instanceof Phoursquare_Venue)
        ) {
            throw new InvalidArgumentException('$venue is no valid Vanue id or ' .
                                               'or instanc eof Phoursquare_Venue');
        }

        if(!is_int($venue) &&
           !is_numeric($venue)
        ) {
            $venue = $venue->getId();
        }

        $data = $this->getRequest()
                     ->sendCheckin((int)$venue, $options);

        if(!property_exists($data, 'checkin')) {
            throw new Exception('No valid checkin response returned.');
        }

        require_once 'Phoursquare/Checkin/Action.php';
        return new Phoursquare_Checkin_Action(
            $data->checkin,
            $this->getAuthenticatedUser()
        );
    }
    
    /**
     *
     * @param integer|Phoursquare_User_AbstractUser $user
     * @return Phoursquare_User_AbstractUser
     */
    public function sendFriendRequest($user)
    {
        $uid  = $this->_checkFriendRequest($user);
        $user = $this->_getUser($uid, time());

        $status = $user->getFriendstatus();
        if($status == 'friend' ||
           $status == 'pendingthem' ||
           $status == 'pendingyou'
        ) {
            return $user;
        }

        try {
            $data = $this->getRequest()
                         ->sendFriendRequest($uid);
        } catch (Exception $e) {
            throw new Exception('Can\'t establish friendship, ' .
                                'the user maybe blocked you');
        }

        return $this->_getUser(
            $data->user->id,
            'pending-friendship-' . $data->user->id
        );
    }

    /**
     *
     * @param integer|Phoursquare_User_AbstractUser $user
     * @return Phoursquare_User_AbstractUser
     */
    public function approveFriendRequest($user)
    {
        $uid  = $this->_checkFriendRequest($user);
        $user = $this->_getUser($uid, time());

        $data = $this->getRequest()
                     ->approveFriendRequest($uid);

        return $this->getUser($data->user->id);
    }

    /**
     *
     * @param integer|Phoursquare_User_AbstractUser $user
     * @return Phoursquare_User_AbstractUser
     */
    public function denyFriendRequest($user)
    {
        $uid  = $this->_checkFriendRequest($user);
        $user = $this->_getUser($uid, time());

        $data = $this->getRequest()
                     ->denyFriendRequest($uid);

        return $this->getUser($data->user->id);
        
    }

    /**
     *
     * @param integer|Phoursquare_User_AbstractUser $user
     * @return Phoursquare_UsersList
     */
    public function getPendingFriendRequests()
    {
        $data = $this->getRequest()
                     ->getPendingFriendRequests();

        if(!property_exists($data, 'requests')) {
            throw new Exception('No valid request response returned.');
        }

        require_once 'Phoursquare/FriendRequestsList.php';
        return new Phoursquare_FriendRequestsList(
            $data->requests,
            $this
        );
    }

    /**
     *
     * @param integer|Phoursquare_User_AbstractUser $user
     * @return void
     */
    protected function _checkFriendRequest($user)
    {
        if(!is_object($user) && !is_int($user) && !is_numeric($user)) {
            throw new InvalidArgumentException('$user is no integer or object');
        }

        if(is_object($user)) {
            if($user instanceof Phoursquare_User_Friend) {
                throw new InvalidArgumentException('$user is already a Friend');
            }

            if(!($user instanceof Phoursquare_User_AbstractUser)) {
                throw new InvalidArgumentException('$user is no valid instance' .
                                                   'of Phoursquare_User_AbstractUser');
            }
        }

        if(is_object($user)) {
            $user = $user->getId();
        }

        return $user;
    }

    /**
     *
     * @param Phoursquare_Venue_Tip $tip
     * @param integer|Phoursquare_Venue $venue
     * @return Phoursquare_Venue_Tip
     */
    public function addTip(Phoursquare_Venue_Tip $tip, $venue = null)
    {
        $related = $tip->getRelatedVenue();
        if(!is_null($venue) &&
           is_object($venue) &&
           $venue instanceof Phoursquare_Venue
        ) {
            $related = $venue;
        }

        if(is_int($venue) ||
           is_numeric($venue)
        ) {
            $related = $this->getVenue($venue);
        }

        if(!is_object($related) ||
           !$related instanceof Phoursquare_Venue
        ) {
            throw new Exception('Please pass-in a venue/id via $venue or ' .
                                'set a Venue via setRelatedVenue on the Tip');
        }

        $type = 'tip';
        if($tip->getMarkedAsToDo()) {
            $type = 'todo';
        }


        $data = $this->getRequest()
                     ->saveTip(
                        $tip->getText(),
                        $venue->getId(),
                        $type
                     );


        if(!property_exists($data, 'tip')) {
            throw new Exception('No valid request response returned.');
        }

        $tip = new Phoursquare_Venue_Tip($data);
        $tip->setRelatedVenue($venue->getId());

        return $tip;
    }

    /**
     *
     * @param Phoursquare_Venue_Tip $tip
     * @return boolean
     */
    public function markTipAsToDo(Phoursquare_Venue_Tip $tip)
    {
        $this->_checkMarking($tid);

        try {
            $data = $this->getRequest()
                         ->markTipAsToDo($tip->getId());
        } catch (Exception $e) {
            throw new Exception('Tip could not eb marked as Todo'.
                                'Already on yout todo list?');
        }

        return true;
    }

    /**
     *
     * @param Phoursquare_Venue_Tip $tip
     * @return boolean
     */
    public function unMarkToDo(Phoursquare_Venue_Tip $tip)
    {
        $this->_checkMarking($tid);
        
        try {
            $data = $this->getRequest()
                         ->unMarkTipToDo($tip->getId());
        } catch (Exception $e) {
            throw new Exception('Maybe it is already unmarked?');
        }

        return true;
    }

    /**
     *
     * @param Phoursquare_Venue_Tip $tip
     * @return boolean
     */
    public function markAsDone(Phoursquare_Venue_Tip $tip)
    {
        $this->_checkMarking($tid);

        try {
            $data = $this->getRequest()
                         ->markTipAsDone($tip->getId());
        } catch (Exception $e) {
            throw new Exception('Maybe it is already marked as done?');
        }

        return true;
    }

    /**
     *
     * @param Phoursquare_Venue_Tip $tip
     * @return void
     */
    protected function _checkMarking($tid)
    {
        if(is_null($tip->getId())) {
            throw new Exception('The Tip must saved before marking it!');
        }
    }



}