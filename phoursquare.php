<?php

defined('PATH')
    || define('PATH', realpath(dirname(__FILE__)));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(PATH . '/src/library'),
    get_include_path(),
)));

require_once 'functions.php';
require_once 'Phoursquare.php';
require_once 'Phoursquare/Auth/Http.php';



$auth    = new Phoursquare_Auth_Http();
$auth->setUsername('sven.eisenschmidt@gmail.com');
$auth->setPassword('svenei86');

$service = new Phoursquare($auth);

$firstFriend = $service->getAuthenticatedUser()->getFriends()->current();
//$user = $service->getUser(191377);

print_p($firstFriend);

