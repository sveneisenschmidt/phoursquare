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
 * @category Request
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses Zend_Http_Client
 * @uses Phoursquare_Response
 * @uses Phoursquare_Cache_ZendCacheWrapper
 * @uses Phoursquare_Cache_AbstractCache
 */

require_once 'Zend/Http/Client.php';
require_once 'Phoursquare/Response.php';

/**
 * Phoursquare_Request
 *
 * @category Request
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Request
{
    /**
     *
     * @var const
     */
    const CACHE_TAG_AUTHENTICATED_USER = 'authenticated-user';
    
    /**
     *
     * @var const
     */
    const CACHE_TAG_USER_HISTORY = 'history-user';

    /**
     *
     * @var const
     */
    const FS_API_URI    = 'http://api.foursquare.com';

    /**
     *
     * @var const
     */
    const GM_API_URI    = 'http://maps.google.com/maps/api';

    /**
     *
     * @var const
     */
    const USER_AGENT = 'Phoursquare:0.1.0.0 DEV-Ubuntu/PHP5.3/Apache2';

    /**
     *
     * @var Zend_Http_Client
     */
    protected $_client;

    /**
     *
     * @var Phoursquare_Auth_AbstractAuth
     */
    protected $_auth;

    /**
     *
     * @var Phoursquare_Cache_AbstractCache
     */
    protected $_cache;

    /**
     *
     * @param Phoursquare_Auth_AbstractAuth $auth
     * @return Phoursquare_Request
     */
     public function setAuth(Phoursquare_Auth_AbstractAuth $auth)
     {
         $this->_auth = $auth;
         return $this;
     }

    /**
     *
     * @return Phoursquare_Auth_AbstractAuth
     */
     public function getAuth()
     {
         return $this->_auth;
     }

    /**
     *
     * @return boolean
     */
     public function hasAuth()
     {
         return is_object($this->_auth) &&
                    ($this->_auth instanceof Phoursquare_Auth_AbstractAuth);
     }

    /**
     *
     * @param Zend_Cache_Core|Phoursquare_Cache_AbstractCache $cache
     * @return Phoursquare_Request
     */
     public function setCache($cache)
     {
        if(!is_object($cache)) {
            throw new Exception('Param $cache is no class instance');
        }

        if($cache instanceof Zend_Cache_Core) {

            require_once 'Phoursquare/Cache/ZendCacheWrapper.php';
            $cache = new Phoursquare_Cache_ZendCacheWrapper($cache);
        }
        
        if($cache instanceof Phoursquare_Cache_AbstractCache) {
            $this->_cache = $cache;
            $this->_cache->setEnableTags(true);
            $this->_cache->setManageIds(true);
            return $this;
        }

        throw new Exception('Param $cache is no instance of Zend_Cache_Core ' .
                            'or Phoursquare_Cache_AbstractCache');
     }

    /**
     *
     * @return Phoursquare_Cache_AbstractCache
     */
     public function getCache()
     {
         return $this->_cache;
     }

    /**
     *
     * @return boolean
     */
     public function hasCache()
     {
         return is_object($this->_cache) &&
                    ($this->_cache instanceof Phoursquare_Cache_AbstractCache);
     }

    /**
     *
     * @return boolean
     */
     public function isReady()
     {
         return !is_null($this->_auth) &&
                     ($this->_auth instanceof Phoursquare_Auth_AbstractAuth) &&
                         $this->_auth->isReady();
     }

     /**
      *
      * @param string $uid
      * @return stdClass
      */
     public function fetchUser($uid = null, $hash = null)
     {
        $client = $this->getClient();
        $client->setUri(self::FS_API_URI . '/v1/user.json');
        if(!is_null($uid) || trim($uid) != "") {
            $client->setParameterGet('uid', (string) $uid);
        } else {
            $uid = self::CACHE_TAG_AUTHENTICATED_USER;
        }

        return $this->_fetch($client, array($uid, $hash));
     }

     /**
      *
      * @param string $fromUserId
      * @return stdClass
      */
     public function fetchFriends($fromUserId = null)
     {
        $client = $this->getClient();
        $client->setUri(self::FS_API_URI . '/v1/friends.json');
        if(!is_null($fromUserId)) {
            $client->setParameterGet('uid', (string) $fromUserId);
        }

        return $this->_fetch($client, array($fromUserId));
     }

     /**
      *
      * @return stdClass
      */
     public function fetchCategories()
     {
        $client = $this->getClient();
        $client->setUri(self::FS_API_URI . '/v1/categories.json');

        return $this->_fetch($client, array());
     }

     /**
      *
      * @param integer $venueId
      * @return stdClass
      */
     public function fetchVenue($venueId)
     {
        $client = $this->getClient();
        $client->setUri(self::FS_API_URI . '/v1/venue.json');
        $client->setParameterGet('vid', (string) $venueId);

        return $this->_fetch($client, array($venueId));
     }

     /**
      *
      * @param integer $limit
      * @param integer $sinceId
      * @return stdClass
      */
     public function fetchHistory($limit = 25, $sinceId = null)
     {
        $client = $this->getClient();
        $client->setUri(self::FS_API_URI . '/v1/history.json');
        $history = self::CACHE_TAG_USER_HISTORY;

        if(!is_null($limit)) {
            if((int)$limit < 1 || (int)$limit > 250) {
                throw new Exception('Limit can only between 1 and 250');
            }
            $client->setParameterGet('l', (string) $limit);
        }
        
        if(!is_null($sinceId)) {
            $client->setParameterGet('sinceid', (string) $sinceId);
        }

        return $this->_fetch($client, array($limit, $sinceId));
     }
     
     /**
      *
      * @param string $uri
      * @param array $parameters
      * @return stdClass
      */
     public function fetchUrl($uri, array $parameters = array())
     {
        $client = $this->getClient();
        $client->setUri(self::FS_API_URI . $uri);

        foreach($parameters as $name => $value) {
            $client->setParameterGet($name, $value);
        }

        return $this->_fetch($client, array_merge(array($uri), $parameters));
     }

     /**
      *
      * @param array $parts
      * @return Phoursquare_GeoLocation 
      */
     public function resolveAddress(array $parts)
     {
        if(empty($parts)) {
            throw new InvalidArgumentException('Address parts can not be empty!');
        }
 
        $client = $this->getClient();
        $client->setUri(self::GM_API_URI . '/geocode/json');
        $client->setParameterGet('address', implode(', ', $parts));
        $client->setParameterGet('sensor', 'false');

        try {
            $data = $this->_fetch($client, $parts);
        } catch (Exception $e) {
            throw new Exception('Google GeoCoder threw an Exception, ' .
                                'unable to return a valid GeoLocation');
        }

        return $data;
     }

     /**
      *
      * @param integer $venueId
      * @param array $options Options are shout, private, twitter, facebook
      * @return stdClass
      */
     public function sendCheckin($venueId, array $options = array())
     {
        if(!is_int($venueId)) {
            throw new InvalidArgumentException('Given-in $venueId is no integer!');
        }

        $client = $this->getClient();
        $client->setMethod(Zend_Http_Client::POST);
        $client->setUri(self::FS_API_URI . '/v1/checkin.json');


        $client->setParameterPost('vid', (string)$venueId);

        if(array_key_exists('shout', $options)) {
            $client->setParameterPost('shout', (string)$options['shout']);
        }

        if(array_key_exists('twitter', $options)) {
            $client->setParameterPost('twitter', (bool)$options['twitter']);
        }

        if(array_key_exists('facebook', $options)) {
            $client->setParameterPost('facebook', (bool)$options['facebook']);
        }

        if(array_key_exists('private', $options)) {
            $client->setParameterPost('private', (bool)$options['private']);
        }

        if($this->hasCache()) {
            $this->getCache()->deleteAllByTag(array(
                self::CACHE_TAG_AUTHENTICATED_USER,
                self::CACHE_TAG_USER_HISTORY,
                $venueId
            ));
        }

        return $this->_fetch($client, $options, false);
     }

     /**
      *
      * @param integer $uid
      * @return stdClass
      */
     public function sendFriendRequest($uid)
     {
        if(!is_int($uid) && !is_numeric($uid)) {
            throw new InvalidArgumentException('Given-in $uid is no integer');
        }

        $client = $this->getClient();
        $client->setMethod(Zend_Http_Client::POST);
        $client->setUri(self::FS_API_URI . '/v1/friend/sendrequest.json');

        $client->setParameterPost('uid', (string)$uid);

        return $this->_fetch($client, array(), false);
     }

     /**
      *
      * @param integer $uid
      * @return stdClass
      */
     public function approveFriendRequest($uid)
     {
        if(!is_int($uid) && !is_numeric($uid)) {
            throw new InvalidArgumentException('Given-in $uid is no integer');
        }

        $client = $this->getClient();
        $client->setMethod(Zend_Http_Client::POST);
        $client->setUri(self::FS_API_URI . '/v1/friend/approve.json');

        $client->setParameterPost('uid', (string)$uid);

        return $this->_fetch($client, array(), false);
     }

     /**
      *
      * @param integer $uid
      * @return stdClass
      */
     public function denyFriendRequest($uid)
     {
        if(!is_int($uid) && !is_numeric($uid)) {
            throw new InvalidArgumentException('Given-in $uid is no integer');
        }

        $client = $this->getClient();
        $client->setMethod(Zend_Http_Client::POST);
        $client->setUri(self::FS_API_URI . '/v1/friend/deny.json');

        $client->setParameterPost('uid', (string)$uid);

        return $this->_fetch($client, array(), false);
     }

     /**
      *
      * @return stdClass
      */
     public function getPendingFriendRequests()
     {
        $client = $this->getClient();
        $client->setUri(self::FS_API_URI . '/v1/friend/requests.json');

        return $this->_fetch($client, array(), false);
     }

     /**
      *
      * @param string $text
      * @param integer $venueId
      * @param string $type
      * @return stdClass
      */
     public function saveTip($text, $venueId, $type = 'todo')
     {
        if(!is_string($text)) {
            throw new InvalidArgumentException('Given-in $text is no string');
        }
        if(empty($text) || trim($text) == '') {
            throw new InvalidArgumentException('Given-in $text is empty');
        }

        if(!is_int($venueId) && !is_numeric($venueId)) {
            throw new InvalidArgumentException('Given-in $venueId is no integer');
        }

        $client = $this->getClient();
        $client->setMethod(Zend_Http_Client::POST);
        $client->setUri(self::FS_API_URI . '/v1/addtip.json');

        $client->setParameterPost('vid', $venueId);
        $client->setParameterPost('text', $text);
        $client->setParameterPost('type', $type);

        if($this->hasCache()) {
            $this->getCache()->deleteAllByTag(array(
                $venueId
            ));
        }

        return $this->_fetch($client, array(), false);
     }

     /**
      *
      * @param integer $tipId
      * @return stdClass
      */
     public function markTipAsToDo($tipId)
     {
        if(!is_int($tipId) && !is_numeric($tipId)) {
            throw new InvalidArgumentException('Given-in $tipId is no integer');
        }

        $client = $this->getClient();
        $client->setMethod(Zend_Http_Client::POST);
        $client->setUri(self::FS_API_URI . '/v1/tip/marktodo.json');

        $client->setParameterPost('tid', (string)$tipId);

        if($this->hasCache()) {
            $this->getCache()->deleteAllByTag(array(
                $tipId
            ));
        }

        return $this->_fetch($client, array(), false);
     }

     /**
      *
      * @param integer $tipId
      * @return stdClass
      */
     public function unMarkTipToDo($tipId)
     {
        if(!is_int($tipId) && !is_numeric($tipId)) {
            throw new InvalidArgumentException('Given-in $tipId is no integer');
        }

        $client = $this->getClient();
        $client->setMethod(Zend_Http_Client::POST);
        $client->setUri(self::FS_API_URI . '/v1/tip/unmark.json');

        $client->setParameterPost('tid', (string)$tipId);

        if($this->hasCache()) {
            $this->getCache()->deleteAllByTag(array(
                $tipId
            ));
        }

        return $this->_fetch($client, array(), false);
     }

     /**
      *
      * @param integer $tipId
      * @return stdClass
      */
     public function markTipAsDone($tipId)
     {
        if(!is_int($tipId) && !is_numeric($tipId)) {
            throw new InvalidArgumentException('Given-in $tipId is no integer');
        }

        $client = $this->getClient();
        $client->setMethod(Zend_Http_Client::POST);
        $client->setUri(self::FS_API_URI . '/v1/tip/markdone.json');

        $client->setParameterPost('tid', (string)$tipId);

        if($this->hasCache()) {
            $this->getCache()->deleteAllByTag(array(
                $tipId
            ));
        }

        return $this->_fetch($client, array(), false);
     }

     /**
      *
      * @param Zend_Http_Client $client
      * @param array $client
      * @param boolean $client
      * @return stdClass
      */
     protected function _fetch(Zend_Http_Client $client, array $ids = array(), $useCache = true)
     {
        if($useCache && $this->hasCache() && $cache = $this->getCache()) {
            $ids[] = $client->getUri(true);
            $hash  = sha1(implode('', $ids));

            if($cache->contains($hash)) {
                $client->resetParameters(true);
                return Phoursquare_Response::decode(
                    $cache->fetch($hash)
                );
            }
        }

        $response = new Phoursquare_Response(
            $client->request()
        );

        if(!$response->isSuccessful()) {
            throw new Exception($response->getErrorMessage());
        }

        $client->resetParameters(true);
        if($useCache && $this->hasCache()) {
            $cache->save($hash, $response->getResponseBody(), false, $ids);
        }
        return Phoursquare_Response::decode(
            $response->getResponseBody()
        );
     }


     /**
      *
      * @param Zend_Http_Client $client
      * @return Phoursquare_Request
      */
     public function setClient(Zend_Http_Client $client)
     {
        $this->_client = $client;
        return $this;
     }

     /**
      *
      * @return Zend_Http_Client
      */
     public function getClient()
     {
        if(is_null($this->_client)) {
            $this->setClient(
                new Zend_Http_Client()
            );

            $this->_client->setHeaders(array(
                'User-Agent' => self::USER_AGENT
            ));

            if(!$this->isReady()) {
                throw new Exception('Your Auth adapter is not accordingly configured or none is given-in.');
            }

            if($this->_auth instanceof Phoursquare_Auth_Http) {
                $this->_client->setAuth(
                    $this->_auth->getUsername(),
                    $this->_auth->getPassword()
                );
            }
        }
        $this->_client->setMethod(
                Zend_Http_Client::GET
        );
        return $this->_client;
     }
     

}