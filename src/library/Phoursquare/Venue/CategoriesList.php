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
 * @uses Phoursquare_Venue_AbstractMemberList
 * @uses Phoursquare_Venue_Category
 */

require_once 'Phoursquare/Venue/AbstractMemberList.php';
require_once 'Phoursquare/Venue/Category.php';

/**
 * Phoursquare_Venue_CategoriesList
 *
 * @category ResultSet
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Venue_CategoriesList 
    extends Phoursquare_Venue_AbstractMemberList
{
    /**
     *
     * @return Phoursquare_Venue_Tip
     */
    protected function _parse($key)
    {
        return new Phoursquare_Venue_Category(
            $this->_data[$key],
            $this->_venue,
            $this->getService()
        );
    }

    /**
     *
     * @return Phoursquare_Venue_Tip
     */
    public function  current()
    {
        return parent::current();
    }

    /**
     *
     * @return Phoursquare_Venue_Tip
     */
    public function getFirstInList()
    {
        return parent::getFirstInList();
    }

    /**
     *
     * @return Phoursquare_Venue_Tip
     */
    public function getLastInList()
    {
        return parent::getLastInList();
    }

}