<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../init.php';

use amzSatellite\APIConnection;

$api = new APIConnection();

$response = $api->getOffersByASIN('B07H8Q3JH9', 'ATVPDKIKX0DER', 'New', "Consumer");

// echo $response;

?>