<?php



declare(strict_types=1);



// require __DIR__ . '/../../vendor/autoload.php';

// require __DIR__ . '/../Handler.php';

// require __DIR__ . '/../Audit.php';

// require __DIR__ . '/../APIConnection.php';

// require __DIR__ . '/../Formatter.php';

// require __DIR__ . '/../Parser.php';

require __DIR__ . '/../ShippingRates.php';



use amzSatellite\Audit;
use amzSatellite\ShippingRates;



// var_dump(ShippingRates::getShippingRates(4, 6, 6, 32, "06415", "90210", "fedex", "fedex_home_delivery", true));

// var_dump(ShippingRates::getShippingRates("ups", "ups_next_day_air", "06415", "90210", 32, 6, 6, 6));

var_dump(ShippingRates::getComprehensiveAverage(16, 8, 4, 4));


// var_dump(ShippingRates::getShippingRates("ups", "ups_next_day_air", "06415", "68154", 32, 6, 6, 6));





// $api = new APIConnection();


// $audit = new audit();

// $audit->audit();

// $reports = $api->listReports("allListings");

// $doc = $api->getReportDocument("amzn1.spdoc.1.4.na.cd75156d-e60e-47af-bba4-3a8926b9ab5e.TKXVKFH3B5PO7.47700");


// $search = $api->bigCatalogSearch(["Fishing Tackle", "Fishing", "Bass Fishing"], 10);

// $search = $api->searchCatalogItems(["Fishing Tackle", "Fishing", "Bass Fishing"]);  

// file_put_contents("search.json", json_encode($search));

// file_put_contents("search.json", $search); 

// var_dump($search);


// var_dump($doc);

// $result = $api ->getReport("179522019817")->json();

// if ($result["processingStatus"] === "DONE") {
//     echo "Done Processing";
// }


// $response = $api->listReports("allListings");

// var_dump($response);

// $reqArr = ["B085J1NB3X","B085J29JG1","B0CP6CWCJC","B0CP69P72B","B085J1F1WF","B085J1C25H","B0CP6BY19L","B0CP6B2SFW","B085J2CP53","B0CP6B7WF5","B0CP6B9SMP","B0CPFYCQYP","B0CPGB4CQG","B0CPGDT6P9","B085JKC34J","B085JK7D4M","B0CLV1Y2RX","B0CLTYX9DN","B085JKC34X","B085JJZQ31","B0CLV12DWT","B0CLTZ5HCG","B085JKC9JM","B085JKB187",
// "B0CLV1MQRM",
// "B085K32J4R",
// "B085JQXWCM",
// "B08JHC1KFH",
// "B0CLTZ35RL"];

// $formatter = new formatReqs();

// $payloadArr = $formatter->arrayToOfferRequest($reqArr);

// file_put_contents('format.json', json_encode($payloadArr));





// $response = $api->getOffersByASIN('B0CVSDGP5J', 'ATVPDKIKX0DER', 'New', "Consumer");

// $payload = new offerRequest('B0CVSDGP5J', 'ATVPDKIKX0DER', 'New', 'Consumer', 'Offers');

// $handler = new requestHandler();

// $handler->bulkAddRequest($payloadArr);

// $handler->showQueue();

// $result = $handler->processQueue();

// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);
// $handler->addRequest($payload);

// $handler->showQueue();

// $result = $handler->processQueue();

// $report = $api->createReports("allListings");

// $attempt = $api->getReport("179522019817")->json();

// var_dump($attempt);

// var_dump($result);

// $count = $handler->getRequestCount();

// echo $count;

// echo $payload->reqType;

// $handler->showQueue();

?>