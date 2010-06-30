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
 * @category ResultSet
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 * 
 */

/**
 * Phoursquare_Checkin_Action_ScoresList
 *
 * @category ResultSet
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Checkin_Action_Score
{
    /**
     *
     * @var integer
     */
    protected $_points = 0;

    /**
     *
     * @var string
     */
    protected $_message;

    /**
     *
     * @var string
     */
    protected $_icon;

    /**
     *
     * @param stdClass $data
     * @param Phoursquare_Service $service
     * @param Phoursquare_Checkin $checkin
     * @param
     */
    public function __construct(
        stdClass $data,
        Phoursquare_Service $service,
        Phoursquare_Checkin $checkin)
    {
        $this->_checkin  = $checkin;
        $this->_service  = $service;

        if(property_exists($data, 'points')) {
            $this->_points = (int)$data->points;
        }

        if(property_exists($data, 'message')) {
            $this->_message = $data->message;
        }

        if(property_exists($data, 'icon')) {
            $this->_icon = $data->icon;
        }
    }

    /**
     *
     * @return Phoursquare_Checkin_Action
     */
    public function getCheckin()
    {
        return $this->_checkin;
    }

    /**
     *
     * @return Phoursquare_User_AbstractUser
     */
    public function getUser()
    {
        return $this->getCheckin()
                    ->getUser();
    }

    /**
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->_points;
    }

    /**
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     *
     * @return string
     */
    public function getIconUrl()
    {
        return $this->_icon;
    }
}