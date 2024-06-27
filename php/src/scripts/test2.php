<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../.env")->load();

use amzSatellite\UtilDBConnection;
use amzSatellite\APIConnection;
use amzSatellite\Processor;
use amzSatellite\ShopDBConnection;

$shop = new ShopDBConnection();

$oldlymeID = json_decode($_SERVER['OLD_LYME_LOCATION_ID']);

$westbrookID = json_decode($_SERVER['WESTBROOK_LOCATION_ID']);

$excludedID = json_decode($_SERVER['EXCLUDED_LOCATION_ID']);

$data = $shop->getInventoryInfo();

$result = [];

// Iterate through the data array
foreach ($data as $item) {
    $barcode = $item['barcode'];
    $location_id = $item['location_id'];
    $available = $item['available'];
    $location_name;


    if (in_array($location_id, $excludedID)) {
        continue;
    }

    // If location id is in array of old lyme location ids, set location name to old lyme
    if (in_array($location_id, $oldlymeID)) {
        $location_name = "Old Lyme";
        var_dump($location_name, $location_id);
    }

    //If location id is in array of westbrook location ids, set location name to westbrook
    if (in_array($location_id, $westbrookID)) {
        $location_name = "Westbrook";
        var_dump($location_name, $location_id);
    }

    // Check if the barcode already exists in the result array
    if (!isset($result[$barcode])) {
        // If not, create a new entry for the barcode
        $result[$barcode] = [
            'title' => $item['title'],
            'barcode' => $barcode,
            'price' => $item['price'],
            'inventory' => []
        ];
    }
    // Add the location ID and available quantity to the inventory array of the corresponding barcode
    $result[$barcode]['inventory'][] = [
        'location_id' => $location_id,
        'location_name' => $location_name,
        'available' => $available
    ];

    //Combine all the inventory items with the same location Name into one array
    $result[$barcode]['inventory'] = array_values(array_reduce($result[$barcode]['inventory'], function ($carry, $item) {
        $location_name = $item['location_name'];
        if (!isset($carry[$location_name])) {
            $carry[$location_name] = [
                'location_id' => $item['location_id'],
                'location_name' => $location_name,
                'available' => 0
            ];
        }
        $carry[$location_name]['available'] += $item['available'];
        return $carry;
    }, []));
}

// Convert the associative array to a simple array of objects
$result = array_values($result);

// Output the result array
print_r($result);
