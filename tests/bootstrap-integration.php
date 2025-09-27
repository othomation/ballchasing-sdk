<?php

// Bootstrap file for integration tests
// Loads .env file if it exists for real API testing

require_once __DIR__.'/../vendor/autoload.php';

// Load .env file for integration testing
$envFile = __DIR__.'/../.env';
if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
    $dotenv->safeLoad();
}
