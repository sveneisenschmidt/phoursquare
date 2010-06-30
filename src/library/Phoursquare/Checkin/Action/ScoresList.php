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
 * @uses Phoursquare_AbstractResultSet
 * @uses Phoursquare_Checkin_Action_Score
 */

require_once 'Phoursquare/AbstractResultSet.php';
require_once 'Phoursquare/Checkin/Action/Score.php';

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
class Phoursquare_Checkin_Action_ScoresList extends Phoursquare_AbstractResultSet
{
    /**
     *
     * @var Phoursquare_Checkin_Action
     */
    protected $_checkin;

    /**
     *
     * @param array $data
     * @param Phoursquare_Service $service
     * @param Phoursquare_Checkin_Action $checkin
     */
    public function __construct(
        array $data,
        Phoursquare_Service $service,
        Phoursquare_Checkin_Action $checkin
    )
    {
        parent::__construct($data, $service);
        $this->_checkin = $checkin;
    }

    /**
     *
     * @return Phoursquare_Checkin_Action_Score
     */
    protected function _parse($key)
    {
        return new Phoursquare_Checkin_Action_Score(
            $this->_data[$key],
            $this->getService(),
            $this->getCheckin()
        );
    }

    /**
     *
     * @return Phoursquare_Checkin_Action_Score
     */
    public function  current()
    {
        return parent::current();
    }

    /**
     *
     * @return Phoursquare_Checkin_Action_Score
     */
    public function getFirstInList()
    {
        return parent::getFirstInList();
    }

    /**
     *
     * @return Phoursquare_Checkin_Action_Score
     */
    public function getLastInList()
    {
        return parent::getLastInList();
    }

    /**
     *
     * @return Phoursquare_Checkin_Action
     */
    public function getCheckin()
    {
        return $this->_checkin;
    }
}