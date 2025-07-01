<?php

require_once BASE_PATH . '/bootstrap.php';
require_once BASE_PATH . '/vendor/autoload.php';

/**
 * Load environment variables from .env file
 * 
 * This function initializes the Dotenv library to load environment variables
 * from a .env file located at the base path of the application. It sets up
 */

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$databases = [
    'pgHost' => $_ENV['PG_HOST'],
    'pgPort' => $_ENV['PG_PORT'],
    'pgDB' => $_ENV['PG_DB'],
    'pgUser' => $_ENV['PG_USER'],
    'pgPassword' => $_ENV['PG_PASS'],
    'mURI' => $_ENV['MONGO_URI'],
    'mDB' => $_ENV['MONGO_DB'],
];