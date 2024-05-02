<?php

//Import autoload file
require __DIR__ . '/../../vendor/autoload.php';

//Declare Dotenv
use Dotenv\Dotenv;
use amzSatellite\APIConnection;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../.env")->load();

//Initialize cURL
$curl = curl_init();

//Set cURL options
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://ssapi.shipstation.com/orders?importBatch=ccc21896-2aa8-4f11-94ab-79ad5cc68bb2",
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



//Assign cURL response to variable
$response = json_decode(curl_exec($curl));

//Execute cURL
curl_close($curl);

file_put_contents("order.json",json_encode($response->orders));



// file_put_contents("response.json", json_encode($response));

//Set string to search order ID for to variable
$searchText = $response->orders[0]->customerNotes;

// Regex Pattern
$order_id_pattern = '/\b\d{3}-\d{7}-\d{7}\b/';

//Find Order ID using regex
// if (preg_match($order_id_pattern, $searchText, $matches)) {
//     $amazon_order_id = $matches[0];
//     echo $amazon_order_id;
// } else {
//     echo "No Amazon Order ID found.";
// }

//Initialize APIConnection
// $api = new APIConnection();

// $orderInfo = $api->getOrderByID($amazon_order_id);

// var_dump($orderInfo);



?>