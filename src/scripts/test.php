<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../init.php';

use amzSatellite\APIConnection;

$api = new APIConnection();

$response = $api->getOffersByASIN('B0CVSDGP5J', 'ATVPDKIKX0DER', 'New', "Consumer");

echo $response;

?>