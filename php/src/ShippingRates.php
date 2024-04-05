<?php

declare(strict_types=1);

namespace amzSatellite;


// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/models/rateRequest.php';

use Dotenv\Dotenv;

use amzSatellite\RequestHandler;

use rateRequest;


//Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../.env")->load();

class ShippingRates
{
    public static function getServices($carrier){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ssapi.shipstation.com/carriers/listservices?carrierCode=$carrier",
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
                "Content-Type: application/json"
                ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function getShippingRates($carrier, $service, $fromPostalCode, $toPostalCode, $weight, $length, $width, $height){
        //Initialize Curl
        $curl = curl_init();
        //Set Curl Options
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://ssapi.shipstation.com/shipments/getrates",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{
            \"carrierCode\": \"$carrier\",
            \"fromPostalCode\": \"$fromPostalCode\",
            \"toCountry\": \"US\",
            \"toPostalCode\": \"$toPostalCode\",
            \"serviceCode\": \"$service\",
            \"weight\": {
                \"value\": $weight,
                \"units\": \"ounces\"
            },
            \"dimensions\": {
                \"units\": \"inches\",
                \"length\": $length,
                \"width\": $width,
                \"height\": $height
            },
            \"confirmation\": \"delivery\",
            \"residential\": true
        }",
        CURLOPT_HTTPHEADER => array(
            "Host: ssapi.shipstation.com",
            "Authorization: Basic " . $_ENV["SHIPSTATION_MASTER_KEY"],
            "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }


    public static function getAverageRates(){}

    public static function getComprehensiveAverage($weight, $length, $width, $height){
        $rateReqs = [];
        $handler = new RequestHandler();
        //Form One Day Rate Requests
        array_push($rateReqs, new rateRequest("ups", "ups_next_day_air", "04098", "06415", $weight, $length, $width, $height, "Rates"));
        array_push($rateReqs, new rateRequest("ups", "ups_next_day_air", "04098", "68154", $weight, $length, $width, $height, "Rates"));
        array_push($rateReqs, new rateRequest("ups", "ups_next_day_air", "04098", "90210", $weight, $length, $width, $height, "Rates"));
        //Form Two Day Rate Requests
        array_push($rateReqs, new rateRequest("ups", "ups_2nd_day_air", "04098", "06415", $weight, $length, $width, $height, "Rates"));
        array_push($rateReqs, new rateRequest("ups", "ups_2nd_day_air", "04098", "68154", $weight, $length, $width, $height, "Rates"));
        array_push($rateReqs, new rateRequest("ups", "ups_2nd_day_air", "04098", "90210", $weight, $length, $width, $height, "Rates"));
        //Add Requests to Handler
        $handler->bulkAddRequest($rateReqs);
        //Trigger Processing
        $results = $handler->processQueue();
        //Average the results
        $averageArr = [];
        // foreach ($results as $result) {
        //     var_dump($result);
        // }

        return json_encode($results);     
    }

    
}



?>