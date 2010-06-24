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
 * @uses Phoursquare_User_AbstractAdvancedUser
 */

require_once 'Phoursquare/User/AbstractAdvancedUser.php';

/**
 * Phoursquare_User_AuthenticatedUser
 *
 * @category User
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_User_AuthenticatedUser extends Phoursquare_User_AbstractAdvancedUser
{

    /**
     *
     * @param stdClass $data
     */
    public function __construct(stdClass $data, Phoursquare_Service $service)
    {
        parent::__construct($data, $service);
    }

    /**
     *
     * @param integer $limit
     * @param integer $sinceId
     * @return Phoursquare_CheckinList
     */
    public function getCheckins($limit = 25, $sinceId = null)
    {
        return $this->getService()
                    ->getAuthenticatedUserCheckins($limit);
    }

    /**
     *
     * @return Phoursquare_CheckinList
     */
    public function getLastCheckin()
    {
        return $this->getService()
                    ->getAuthenticatedUserCheckins(1)
                    ->getFirstInList();
    }
}