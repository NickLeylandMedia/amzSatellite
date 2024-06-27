<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../.env")->load();


use amzSatellite\ShopDBConnection;
use League\Csv\Writer;

try {
    //Create a new ShopDBConnection object
    $shop = new ShopDBConnection();
    //Get the location IDs from the environment variables
    $oldlymeID = json_decode($_SERVER['OLD_LYME_LOCATION_ID'], true);
    $westbrookID = json_decode($_SERVER['WESTBROOK_LOCATION_ID'], true);
    $excludedID = json_decode($_SERVER['EXCLUDED_LOCATION_ID'], true);
    //Convert the arrays to sets for faster lookup
    $oldlymeSet = array_flip($oldlymeID);
    $westbrookSet = array_flip($westbrookID);
    $excludedSet = array_flip($excludedID);
    //Get the inventory information from the database
    $data = $shop->getInventoryInfo();
    //Initialize the result array
    $result = [];
    //Check if the data array is set and not empty
    if (isset($data) && !empty($data)) {
        //Iterate through the data array
        foreach ($data as $item) {
            //Get the item details
            $barcode = $item['barcode'];
            $location_id = $item['location_id'];
            $available = $item['available'];
            //Check if the required fields are set
            if (isset($barcode, $location_id, $available)) {
                //If the location ID is in the excluded set, skip the item
                if (isset($excludedSet[$location_id])) {
                    continue;
                }
                //Set the location name based on the location ID
                $location_name = null;
                if (isset($oldlymeSet[$location_id])) {
                    $location_name = "Old Lyme";
                } elseif (isset($westbrookSet[$location_id])) {
                    $location_name = "Westbrook";
                }
                //If the location name remains null, skip the item
                if ($location_name === null) {
                    continue;
                }
                //If the barcode does not exist in the result array, create a new entry
                if (!isset($result[$barcode])) {
                    $result[$barcode] = [
                        'title' => $item['title'],
                        'barcode' => $barcode,
                        'price' => $item['price'],
                        'inventory' => []
                    ];
                }
                //Add the inventory details to the corresponding barcode entry
                $inventory = &$result[$barcode]['inventory'];
                //If the location name does not exist in the inventory array, create a new entry
                if (!isset($inventory[$location_name])) {
                    $inventory[$location_name] = [
                        'location_id' => $location_id,
                        'location_name' => $location_name,
                        'available' => 0
                    ];
                }
                //Increment the available quantity for the location
                $inventory[$location_name]['available'] += $available;
            }
        }
        //Convert the inventory arrays to indexed arrays
        foreach ($result as &$item) {
            $item['inventory'] = array_values($item['inventory']);
        }
        //Convert the result array to indexed array
        $result = array_values($result);
        //Initialize CSV writer
        $csv = Writer::createFromPath('inventory.csv', 'w+');
        //Insert first row with column names
        $csv->insertOne(['barcode', 'price', 'westbrook_inventory', 'old_lyme_inventory']);
        //Iterate the final result array and insert the data into the CSV file
        foreach ($result as $item) {
            //Set properties for insert
            $barcode = $item['barcode'];
            $price = $item['price'];
            $westbrookInv = 0;
            $oldLymeInv = 0;
            //Iterate each individual inventory item and get the available quantity for each location
            foreach ($item['inventory'] as $inventory) {
                if ($inventory['location_name'] === 'Westbrook') {
                    $westbrookInv = $inventory['available'];
                } elseif ($inventory['location_name'] === 'Old Lyme') {
                    $oldLymeInv = $inventory['available'];
                }
            }
            //Insert the data into the CSV file
            $csv->insertOne(
                [
                    $barcode,
                    $price,
                    $westbrookInv,
                    $oldLymeInv
                ]
            );
        }
    }
} catch (Exception $ex) {
    throw new Exception($ex->getMessage());
}



//Return the result array
// var_dump($result);
