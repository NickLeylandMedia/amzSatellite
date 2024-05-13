<?php

//Import autoload file
require __DIR__ . '/../../../vendor/autoload.php';

//Declare Dotenv
use Dotenv\Dotenv;
use amzSatellite\Manifest;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../../.env")->load();

try {
    //Get POST data
    $postData = json_decode(file_get_contents('php://input'), true);
    //Initalize Manifest Class
    $manifest = new Manifest();
    //Add Shipment
    $action = $manifest->createShipment($postData['date'], $postData['name'], $postData['summary']);
    //Retrieve Most Recent Shipment
    $last = $manifest->getMostRecentShipment();
    //Form Payload With Data
    $payload = [
        "message" => "New shipment added to manifest!",
        "shipment" => $last
    ];
    //Return Payload
    echo json_encode($payload);
} catch (Exception $ex) {
    echo json_encode($ex);
}
