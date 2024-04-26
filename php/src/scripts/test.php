<?php



declare(strict_types=1);



require __DIR__ . '/../../vendor/autoload.php';

// require __DIR__ . '/../Formatter.php';

// require __DIR__ . '/../Parser.php';

// require __DIR__ . '/../ShippingRates.php';

// require __DIR__ . '/../FeeHandler.php';



use Blackhalloutfitters\RequestHandler;

use amzSatellite\Audit;
use amzSatellite\ShippingRates;
use amzSatellite\Manifest;
use amzSatellite\Parser;
use amzSatellite\Formatter;
use amzSatellite\APIConnection;
use amzSatellite\DBConnection;
use amzSatellite\Sellers;

// use skuSales;

$api = new APIConnection();

$parser = new Parser();

$db = new DBConnection();

$shipping = new ShippingRates();

$sellers = new Sellers();

// $result = $shipping::getCarriers();

// $rate = $shipping::getShippingRates("stamps_com", "usps_first_class_mail", "06415", "90210", 4, 6, 6, 6);

// $fastAverage = $shipping::getPrimeAverage(19.4, 6, 6, 6);

// $slowAverage = $shipping::getRegularAverage(19.4, 6, 6, 6);

$results = $api->getAmazonOffers("B085JVR1ML"); 

var_dump($results);

// sellers::addSeller("Backcountry", "A2RS6Z4HKBA2G7", "Park City", "UT", "84060", null, "18004094502", null, "backcountry.com");

// $result = $sellers::getSellerByID("AJ67UVA4UQWIB");

// var_dump($result);




// $sell = $sellers::getSellerByID("AU8KF031TC39C");

// $sell = $sellers::listAllSellers();

// var_dump($sell);

// var_dump($results['payload']['Offers']);



// $services = $shipping::getServices("stamps_com");

// $shortData = $parser->loadReportData("1.tsv", 8, false);

// $data = $parser->bulkLoadReportData(["1.tsv", "2.tsv", "3.tsv", "4.tsv", "5.tsv"], 8, true);

// $finalData = $parser->amzShipmentData();

// $sellers::addSeller("Black Hall Outfitters", "AJ67UVA4UQWIB", "Old Lyme", "CT", "06371", "132 Shore Rd", "8604349689", null,"www.blackhalloutfitters.com");

// $sellers::addSeller("Backcountry", "AU8KF031TC39C", "Park City", "UT", "84060", null, "18004094502", null, "backcountry.com");



// var_dump($slowAverage);

// var_dump($finalData);

// var_dump($data);

// $data = $parser->loadReportData("transfer.csv", 0, false);

// $formatted = $parser-> loadSkuData(["DTX220-S-PHT", "210000033923-FBA"]);

// $finalData = $parser->skuReceivedData();

// var_dump($finalData);



// $manifest = new Manifest();



// $data = $manifest-> createShipment("2024-04-06 4:56:32PM", "Test Shipment", "This is a test shipment");

// $manifest->addToShipment(1, "B0BS49SD14", 5);

// foreach ($data as $record) {
//     var_dump($record);
// }

// var_dump(Formatter::stringToDBDate("2024-04-06 4:56:32PM"));

// $manifest->loadReportData("report.csv");

// $manifest->loadSkuData(["DTX220-S-PHT", "210000033923-FBA"]);

// $manifest->shaveSalesData();

// $manifest->displaySkuData();

// $manifest->shaveSalesData();

// $manifest->displaySalesData();



// $handler = new RequestHandler($_ENV["REFRESH_TOKEN"], $_ENV["CLIENT_ID"], $_ENV["CLIENT_SECRET"]);

// $req = $handler->createFeeEstimateRequest(45.00, "B0BS49SD14", true, "USD", 0.00, "ASIN");


// $response = $api->getFeesEstimate("sfdasda", "lkmflksmfkamsdas", true, "USD", "sfdsafsf", "asin", 45, 5);




// var_dump($req);



// $response = $api->getDimensionsByASIN("B083FVPS6L");

// var_dump($response["errors"]["details"]);

// $decode = json_decode($response, true);

// $dimensions = $decode["dimensions"];

// var_dump($response["dimensions"][0]);



// var_dump(ShippingRates::getShippingRates(4, 6, 6, 32, "06415", "90210", "fedex", "fedex_home_delivery", true));

// var_dump(ShippingRates::getShippingRates("ups", "ups_next_day_air", "06415", "90210", 32, 6, 6, 6));

// var_dump(ShippingRates::getComprehensiveAverage(12, 33, 3, 1));

// $date = Formatter::dateToISO("2024-04-06");

// $date = Formatter::dateToISO("2024-04-06");

// $raw = new DateTime($date);

// $rawTime = $raw->getTimestamp();

// var_dump($rawTime);

// $response = $api->scopeFBA();

// $response = $api->getOffersByASIN("B08B43PJ6F", 'New', "Business");

// $response = $api->getDimensionsByASIN("B07X13DBY6");


// $decode = json_decode($response, true);

// var_dump($response);

// var_dump(Formatter::dateToISO("2024-04-06"));


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