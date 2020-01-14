<?php

declare(strict_types=1);

error_reporting(-1);
date_default_timezone_set('UTC');

define('FIXTURES_PATH', __DIR__ . '/fixtures');

// Include the composer autoloader
$loader = require __DIR__ . '/../vendor/autoload.php';
