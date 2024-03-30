<?php

declare(strict_types=1);

require_once __DIR__ . '\vendor\autoload.php';

use Blackhalloutfitters\RequestHandler;
use Dotenv\Dotenv;
use League\Csv\Reader;
use League\Csv\Writer;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$request_handler = new RequestHandler($_ENV["REFRESH_TOKEN"], $_ENV["CLIENT_ID"], $_ENV["CLIENT_ID_SECRET"]);

$reader = Reader::createFromPath($_ENV["INPUT_FILE"], 'r');
$reader->setHeaderOffset(0);
$records = $reader->getRecords();

$writer = Writer::createFromPath($_ENV["OUTPUT_FILE"], 'w+');
$writer->insertOne(["ID Value", "ID Type", "Is FBA?", "Listing Price", "Listing Price Currency Code", "Shipping Price", "Shipping Price Currency Code", "Total Fees", "Total Fees Currency Code"]);

foreach ($records as $offset => $record) {
    if($request_handler->getRequestsCount() == 20){
        usleep(2000000); //https://developer-docs.amazon.com/sp-api/docs/product-fees-api-v0-reference#pricetoestimatefees (1 call per second...ish)
        $writer->insertAll($request_handler->submitFeeEstimateRequests(output: $_ENV["EXPORT_TYPE"] == "details" ? RequestHandler::FEEDETAILS : RequestHandler::TOTALFEES));
    }

    $request_handler->createFeeEstimateRequest(
        id_value: (string) $record["ID Value"],
        id_type: (string) $record['ID Type'],
        marketplace_id: (string) $record["Marketplace ID"],
        is_amazon_fulfilled: strtolower($record["Is FBA?"]) == "true" ? true : false, //https://www.php.net/manual/en/function.boolval
        currency_code: (string) $record["Currency Code"],
        listing_price_amount: (float) $record["Listing Price"],
        shipping_price_amount: (float) $record["Shipping Price"]
    );
}