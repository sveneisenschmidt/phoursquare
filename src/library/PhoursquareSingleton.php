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
 * @uses Phourquare_Service
 */

require_once 'Phoursquare/Service.php';

/**
 * PhoursquareSingleton
 *
 * @category Service
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
final class PhoursquareSingleton extends Phoursquare_Service
{

    /**
     *
     * @var PhoursquareSingleton
     */
    private static $instance;

    /**
     *
     * @return void
     */
    private function __construct() {}

    /**
     *
     * @return void
     */
    private function __clone() {}

    /**
     *
     * @return PhoursquareSingleton
     */
    public static function getInstance() {

       if (self::$instance === NULL) {
           self::$instance = new self;
       }

       return self::$instance;
    }

    /**
     *
     * @return boolean
     */
    public static function hasAuthInstance()
    {
        if(!self::$instance) {
            return false;
        }

        return self::getInstance()->hasAuth();
    }

    /**
     *
     * @return boolean
     */
    public static function hasCacheInstance()
    {
        if(!self::$instance) {
            return false;
        }

        return self::getInstance()->hasCache();
    }
}