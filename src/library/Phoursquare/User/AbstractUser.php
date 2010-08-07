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
 * @category User
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 */

/**
 * Phoursquare_User_AbstractUser
 *
 * @category User
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
abstract class Phoursquare_User_AbstractUser
{
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
    protected $_firstname;

    /**
     *
     * @var string
     */
    protected $_lastname;

    /**
     *
     * @var string
     */
    protected $_photo;

    /**
     *
     * @var string
     */
    protected $_gender;

    /**
     *
     * @var string
     */
    protected $_twitter;

    /**
     *
     * @var string
     */
    protected $_facebook;

    /**
     *
     * @var string
     */
    protected $_friendstatus;

    /**
     *
     * @param stdClass $data
     */
    public function __construct(stdClass $data, Phoursquare_Service $service)
    {
        $this->setService($service);

        $this->fill($data, array(
            'id',
            'firstname',
            'lastname',
            'photo',
            'gender',
            'twitter',
            'facebook',
            'friendstatus'
        ));
    }

    /**
     *
     * @param stdClass $data
     * @return void
     */
    public function fill(stdClass $data, array $whitelist = array())
    {
        if(!property_exists($data, 'id')) {
            throw new Exception('Missing \'id\' poperty.');
        }

        foreach($data as $key => $value) {
            if(!in_array($key, $whitelist)) {
                continue;
            }

            $this->{'_' . $key} = is_numeric($value) ?
                                    (int)$value : $value;
        }
    }

    /**
     *
     * @param Phoursquare_Service $service
     * @return Phoursquare_User_AbstractUser
     */
    public function setService(Phoursquare_Service $service)
    {
        $this->_service = $service;
        return $this;
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
    public function getId()
    {
        return (int)$this->_id;
    }

    /**
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->_firstname;
    }

    /**
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->_lastname;
    }

    /**
     *
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->_photo;
    }

    /**
     *
     * @return string
     */
    public function getGender()
    {
        return $this->_gender;
    }

    /**
     *
     * @return boolean
     */
    public function hasTwitter()
    {
        return (bool)$this->getTwitter();
    }

    /**
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->_twitter;
    }

    /**
     *
     * @return boolean
     */
    public function hasFacebook()
    {
        return (bool)$this->getFacebook();
    }

    /**
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->_facebook;
    }


    /**
     *
     * @return string
     */
    public function getFriendstatus()
    {
        return $this->_friendstatus;
    }

}