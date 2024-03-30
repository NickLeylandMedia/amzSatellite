<?php



declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../init.php';

require_once __DIR__ . '/../handler.php';

require_once __DIR__ . '/../models/offerRequest.php';

use amzSatellite\APIConnection;

use requestHandler\requestHandler;

$api = new APIConnection();

$response = $api->listReports("allListings");

var_dump($response);





// $response = $api->getOffersByASIN('B0CVSDGP5J', 'ATVPDKIKX0DER', 'New', "Consumer");

// $payload = new offerRequest('B0CVSDGP5J', 'ATVPDKIKX0DER', 'New', 'Consumer', 'Offers');

// $handler = new requestHandler();

// $handler->addRequest($payload);
// $handler->addRequest($payload);

// $handler->processQueue();

// $count = $handler->getRequestCount();

// echo $count;

// echo $payload->reqType;

// $handler->showQueue();

?>