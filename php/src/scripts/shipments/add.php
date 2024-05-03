<?php

//Import autoload file
require __DIR__ . '/../../vendor/autoload.php';

//Declare Dotenv
use Dotenv\Dotenv;
use amzSatellite\Manifest;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../../.env")->load();

//Get POST data
$postData = json_decode(file_get_contents('php://input'), true);







?>