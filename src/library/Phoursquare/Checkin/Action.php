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
 * @category Checkin
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses Phoursquare_Venue
 * @uses Phoursquare_Checkin
 * @uses Phoursquare_Checkin_Action_ScoresList
 * @uses Phoursquare_User_AbstractUser
 */

require_once 'Phoursquare/Checkin.php';

/**
 * Phoursquare_Checkin_Action
 *
 * @category Checkin
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Checkin_Action extends Phoursquare_Checkin
{
    /**
     *
     * @var array|Phoursquare_Checkin_Action_ScoresList
     */
    protected $_scores;

    /**
     *
     * @param stdClass $data
     * @param Phoursquare_User_AbstractUser $user
     */
    public function __construct(stdClass $data, Phoursquare_User_AbstractUser $user)
    {
        parent::__construct($data, $user);

        if(property_exists($data, 'scores')) {
            $this->_scores = $data->scores;
        }
    }
    /**
     *
     * @return boolean
     */
    public function hasScores()
    {
        return !is_null($this->_scores);
    }

    /**
     *
     * @return Phoursquare_Checkin_Action_ScoresList
     */
    public function getScores()
    {
        if(!$this->hasScores()) {
            return null;
        }

        if(!($this->_scores instanceof Phoursquare_Checkin_Action_ScoresList)) {
            require_once 'Phoursquare/Checkin/Action/ScoresList.php';
            $this->_scores = new Phoursquare_Checkin_Action_ScoresList(
                $this->_scores,
                $this->getService(),
                $this
            );
        }

        return $this->_scores;
    }

    /**
     *
     * @return integer
     */
    public function getScorePoints()
    {
        $points = 0;

        foreach($this->getScores() as $score) {
            $points += $score->getPoints();
        }

        return $points;
    }
}