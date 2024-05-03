<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../.env")->load();



class ShipstationUpdater
{
    public function updateShipment(string $name, string | null $company, string $street1, string | null $street2, string | null $street3, string $city, string $state, string $postalCode, string $country = "US", string $phone, bool $residential, string $orderKey, string $orderDate, string $orderNumber, string $shipByDate, string $billingName, string | null $billingCompany, $billingStreet1, $billingStreet2, $billingStreet3, $billingCity, $billingState, $billingPostalCode, $billingCountry, $billingPhone, $billingResidential) {
    //Initialize cURL
    $curl = curl_init();

    //Set properties on payload
    $payload = [
        "shipTo" => [
            "name" => $name,
            "company" => $company,
            "street1" => $street1,
            "street2" => $street2,
            "street3" => $street3,
            "city" => $city,
            "state" => $state,
            "postalCode" => $postalCode,
            "country" => $country,
            "phone" => $phone,
            "residential" => $residential
        ],
        "orderKey" => $orderKey,
        "billTo" => [
            "name" => $billingName,
            "company" => $billingCompany,
            "street1" => $billingStreet1,
            "street2" => $billingStreet2,
            "street3" => $billingStreet3,
            "city" => $billingCity,
            "state" => $billingState,
            "postalCode" => $billingPostalCode,
            "country" => $billingCountry,
            "phone" => $billingPhone,
            "residential" => $billingResidential
        ],
        "orderStatus" => "awaiting_shipment",
        "orderDate" => $orderDate,
        "orderNumber" => $orderNumber,
        "shipByDate" => $shipByDate
    ];

    //Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://ssapi.shipstation.com/orders/createorder",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",            CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => array(
                  "Host: ssapi.shipstation.com",
                  "Authorization: Basic " . $_ENV["SHIPSTATION_MASTER_KEY"],
                  "Content-Type: application/json"
                ),
    ));

  
    $response = curl_exec($curl);
    curl_close($curl);
    var_dump($response);
    }
    
   
}


?>