<?php

/**
 * Router for PHP's built-in web server.
 *
 * @see https://www.php.net/manual/en/features.commandline.webserver.php
 * @see https://symfony.com/doc/current/setup/symfony_server.html#using-the-php-built-in-web-server
 */

if (is_file($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$_SERVER['SCRIPT_NAME'])) {
    return false;
}

$script = $_ENV['APP_FRONT_CONTROLLER'] ?? 'index.php';

$_SERVER = array_merge($_SERVER, $_ENV);
$_SERVER['SCRIPT_FILENAME'] = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$script;
$_SERVER['SCRIPT_NAME'] = DIRECTORY_SEPARATOR.$script;
$_SERVER['PHP_SELF'] = DIRECTORY_SEPARATOR.$script;

require $_SERVER['SCRIPT_FILENAME'];
