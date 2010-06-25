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
 * @category Response
 * @package Phoursquare
 *
 * @license MIT-Style License
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @link www.unsicherheitsagent.de
 *
 * @uses Zend_Json
 */

require_once 'Zend/Json.php';

/**
 * Phoursquare_Response
 *
 * @category Response
 * @package Phoursquare
 * @author Sven Eisenschmidt <sven.eisenschmidt@gmail.com>
 * @copyright 2010, Sven Eisenschmidt
 * @license MIT-Style License
 * @link www.unsicherheitsagent.de
 */
class Phoursquare_Response
{
    /**
     *
     * @var Zend_Http_Response
     */
    protected $_response;
    /**
     *
     * @var string
     */
    protected $_error;

    /**
     *
     * @param Zend_Http_Response $response
     */
    public function __construct(Zend_Http_Response $response = null)
    {
        if(!is_null($response)) {
            $this->setClientResponse($response);
        }
    }

    /**
     *
     * @param Zend_Client_Response $response
     * @return Phoursquare_Response
     */
    public function setClientResponse(Zend_Http_Response $response )
    {
        $this->_response = $response;
        return $this;
    }

    /**
     *
     * @return Zend_Http_Response
     */
    public function getClientResponse()
    {
        return $this->_response;
    }

    /**
     *
     * @param string $msg
     * @return Phoursquare_Response
     */
    public function setErrorMessage($msg)
    {
        $this->_error = (string)$msg;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->_error;
    }

    /**
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if(!$this->getClientResponse()->isSuccessful()) {
            $this->setErrorMessage(
                $this->getClientResponse()->getMessage()
            );
            return false;
        }

        return true;
    }

    /**
     *
     * @return string
     */
    public function getResponseBody()
    {
        return $this->getClientResponse()
                    ->getBody();
    }

    /**
     *
     * @return stdClass
     */
    public static function decode($string)
    {
        return Zend_Json::decode(
            $string,
            Zend_Json::TYPE_OBJECT
        );
    }


}