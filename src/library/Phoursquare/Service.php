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
 * @uses Phourquare_Cache_AbstractCache
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
    private $_request;
    
    /**
     *
     * @var Phoursquare_Auth_Http
     */
    private $_auth;

    /**
     *
     * @param Phoursquare_Auth_Http $auth
     */
    public function __construct(Phoursquare_Auth_AbstractAuth $auth = null)
    {
        if(!is_null($auth)) {
            $this->setAuth($auth);
        }
    }

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
     * @return Phoursquare_ResultSet
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
                     ->fetchUser((string)$uid, $hash);

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
           $data->user->friendstatus = 'friend'
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



}