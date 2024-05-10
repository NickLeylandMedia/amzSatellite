<?php

//Import autoload file
require __DIR__ . '/../../vendor/autoload.php';

//Declare Dotenv
use Dotenv\Dotenv;
use amzSatellite\APIConnection;
use amzSatellite\ShipstationUpdater;
use Monolog\ErrorHandler;
use Monolog\Logger;

//Initialize Logger
$logger = new Logger("primeLog");
//Register Logger as Error Handler
ErrorHandler::register($logger);

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../.env")->load();

try {
  //Get POST data
  $postData = json_decode(file_get_contents('php://input'), true);

  //Assign resource URL to variable
  $resourceURL = $postData['resource_url'];

  //Initialize cURL
  $curl = curl_init();

  //Throw an error if there is no resource url included in the post data.
  if (!isset($resourceURL)) {
    throw new Exception("No resource URL provided.");
  }

  //If a resource URL is provided, set cURL options
  if (isset($resourceURL)) {
    //Set cURL options
    curl_setopt_array($curl, array(
      CURLOPT_URL => $resourceURL,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Host: ssapi.shipstation.com",
        "Authorization: Basic " . $_ENV["SHIPSTATION_MASTER_KEY"],
      ),
    ));
  }

  //Assign cURL response to variable
  $response = json_decode(curl_exec($curl));

  //Array to store orders which are updated
  $orders = [];

  //Iterate through orders retrieved from payload
  foreach ($response->orders as $order) {
    //Extract custom field 3 from ShipStation Order
    $string = $order->advancedOptions->customField3;

    //Throw an error if custom field 3/order data is not found
    if (!isset($string)) {
      $logger->warning("Custom field not found / no order data found.");
    }
    //Log if an online store order is found
    if (isset($string) && str_contains($string, "online store")) {
      $logger->log(200, "Online Store Order. No Processing needed.");
    }

    // Extract Order ID from ShipStation Order
    if (isset($string) && str_contains($string, "amazon")) {
      //Explode the field string to extract info
      $explString = explode(",", $string);
      //Select order ID from exploded string
      $orderRaw = $explString[1];
      //Remove whitespace from string
      $procString = preg_replace('/\s+/', '', $orderRaw);
      //Extract order ID from non-spaced processed string
      $amzOrderID = substr($procString, 18);
    }

    //Extract Order ID from Ebay Order
    if (isset($string) && str_contains($string, "ebay")) {
      //Explode the field string to extract info
      $explString = explode(",", $string);
      //Select order ID from exploded string
      $orderRaw = $explString[1];
      //Remove whitespace from string
      $procString = preg_replace('/\s+/', '', $orderRaw);
      //Extract order ID from non-spaced processed string
      $ebayOrderID = substr($procString, 18);
    }

    //Initialize Amazon API Connection to get order shipment info
    if (isset($amzOrderID)) {
      //Initialize API Connection
      $api = new APIConnection();
      //Get Order Info by Order ID
      $orderInfo = $api->getOrderByID($amzOrderID);
    }

    //If order info is successfully retrieved, extract purchase date, latest ship date, and latest delivery date
    if (isset($orderInfo) && isset($amzOrderID) && isset($orderInfo['payload']['PurchaseDate']) && isset($orderInfo['payload']['LatestShipDate']) && isset($orderInfo['payload']['LatestDeliveryDate'])) {
      file_put_contents("orderInfo.json", json_encode($orderInfo));
      //Amazon Order Information to Post to ShipStation
      $purchaseDate = $orderInfo['payload']['PurchaseDate'];
      $latestShipDate = $orderInfo['payload']['LatestShipDate'];
      $latestDeliveryDate = $orderInfo['payload']['LatestDeliveryDate'];

      //Push order info to orders array
      array_push($orders, [
        "platform" => "amazon",
        "orderID" => $amzOrderID,
        "purchaseDate" => $purchaseDate,
        "latestShipDate" => $latestShipDate,
        "latestDeliveryDate" => $latestDeliveryDate
      ]);

      //Update ShipStation Order with Amazon Order Info
      // $updater = new ShipstationUpdater();
      // $updater->updateShipment($order->shipTo->name, $order->shipTo->company, $order->shipTo->street1, $order->shipTo->street2, $order->shipTo->street3, $order->shipTo->city, $order->shipTo->state, $order->shipTo->postalCode, $order->shipTo->country, $order->shipTo->phone, $order->shipTo->residential, $order->orderKey, $order->orderDate, $order->orderNumber, $latestShipDate, $order->billTo->name, $order->billTo->company, $order->billTo->street1, $order->billTo->street2, $order->billTo->street3, $order->billTo->city, $order->billTo->state, $order->billTo->postalCode, $order->billTo->country, $order->billTo->phone, $order->billTo->residential);
    }
  }
  $logger->info("Orders successfully processed.");
} catch (\Exception $exception) {
  $logger->error($exception->getMessage());
  echo $exception->getMessage();
}

//Write orders to JSON file
file_put_contents("orders.json", json_encode($orders));

//Close curl
curl_close($curl);
