<?php

defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__) . '/..'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(BASE_PATH . '/src/lib'),
    realpath(BASE_PATH . '/tests/src/lib'),
    get_include_path(),
)));