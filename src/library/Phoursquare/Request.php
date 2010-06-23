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
    const API_URI    = 'http://api.foursquare.com';

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
     public function fetchUser($uid = null)
     {
        $client = $this->getClient();
        $client->setUri(self::API_URI . '/v1/user.json');
        if(!is_null($uid)) {
            $client->setParameterGet('uid', (string) $uid);
        }

        return $this->_fetch($client);
     }

     /**
      *
      * @param string $fromUserId
      * @return stdClass
      */
     public function fetchFriends($fromUserId = null)
     {
        $client = $this->getClient();
        $client->setUri(self::API_URI . '/v1/friends.json');
        if(!is_null($fromUserId)) {
            $client->setParameterGet('uid', (string) $fromUserId);
        }

        return $this->_fetch($client);
     }

     /**
      *
      * @param Zend_Http_Client $client
      * @return stdClass
      */
     protected function _fetch(Zend_Http_Client $client)
     {
        $response = new Phoursquare_Response(
            $client->request()
        );

        if(!$response->isSuccessful()) {
            throw new Exception($response->getErrorMessage());
        }

        $client->resetParameters(true);
        return $response->decode();
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
        return $this->_client;
     }
     

}