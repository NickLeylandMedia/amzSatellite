<?php

//Import autoload file
require __DIR__ . '/../../../vendor/autoload.php';

//Declare Dotenv
use Dotenv\Dotenv;
use amzSatellite\Manifest;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../../.env")->load();

try {
    //Initalize Manifest Class
    $manifest = new Manifest();
    //Get POST data
    $postData = json_decode(file_get_contents('php://input'), true);
} catch (Exception $ex) {
}
