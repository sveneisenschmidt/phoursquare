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
 * @category Auth
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses Phoursquare_Auth_AbstractAuth
 */

require_once 'Phoursquare/Auth/AbstractAuth.php';

/**
 * Phoursquare_Auth_Http
 *
 * @category Auth
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Auth_Http extends Phoursquare_Auth_AbstractAuth
{


    /**
     *
     * @var string
     */
    protected $_username;

    /**
     *
     * @var string
     */
    protected $_password;

    /**
     *
     * @param string $username
     * @return Phoursquare_Request
     */
    public function setUsername($username = null)
    {
        if(!is_null($username) && !is_string($username)) {
            throw new InvalidArgumentException('Given-in $usernam is no string or null.');
        }

        $this->_username = (string)$username;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getUsername()
    {
        return is_string($this->_username) ?
                    $this->_username : (string) $this->_username;
    }

    /**
     *
     * @param string $password
     * @return Phoursquare_Request
     */
    public function setPassword($password = null)
    {
        if(!is_null($password) && !is_string($password)) {
            throw new InvalidArgumentException('Given-in $usernam is no string or null.');
        }

        $this->_password = (string)$password;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getPassword()
    {
        return is_string($this->_password) ?
                    $this->_password : (string) $this->_password;
    }

    /**
     *
     * @return boolean
     */
    public function isReady()
    {
        return !empty($this->_username) &&
                   !empty($this->_password);
    }

}