<?php

use Vendor\Core;
use Vendor\Config;

define('Core', require $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/vendor/Core.php');

Core::setConfig('main', $_SERVER['DOCUMENT_ROOT'] . '/config/main.php');
Core::setConfig('app', $_SERVER['DOCUMENT_ROOT'] . '/config/app.php');

Core::run();
