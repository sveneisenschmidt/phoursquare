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
 * @category   Test, Loader
 * @package    Phoursquare
 *
 * @license    MIT style license
 * @author     Sven Eisenschmidt   <sven.eisenschmidt@gmail.com>
 * @copyright  2010, Sven Eisenschmidt
 * @link       www.unsicherheitsagent.de
 */

require_once 'Phoursquare/Loader.php';

class Phoursquare_LoaderTest extends PHPUnit_Framework_TestCase {

    
    /**
     *
     * @return void
     */
    public function setUp()
    {
        Phoursquare_Loader::resetInstance();
    }

    /**
     *
     * @test
     */
    public function resetReturnsNewClassInstance()
    {

        $loader1 = Phoursquare_Loader::getInstance();
        $hash1   = spl_object_hash($loader1);

        Phoursquare_Loader::resetInstance();

        $loader2 = Phoursquare_Loader::getInstance();
        $hash2   = spl_object_hash($loader2);

        $this->assertNotEquals($hash1, $hash2);
    }

    /**
     *
     * @test
     */
    public function getInstanceRerurnsAlwaysSameFirstInstance()
    {
        $this->assertEquals(
            spl_object_hash(Phoursquare_Loader::getInstance()),
            spl_object_hash(Phoursquare_Loader::getInstance())
        );
    }

  
}