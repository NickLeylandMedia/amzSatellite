<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/models/asinSku.php';

use DB;
use Exception;
use amzSatellite\Parser;
use amzSatellite\ShopDBConnection;
use amzSatellite\UtilDBConnection;
use asinSku;



class Loader
{ 
    public function buildAsinAssoc($reportFile) {
        $parser = new Parser();
        $shopDB = new ShopDBConnection();
        $utilityDB = new UtilDBConnection();
        $parser->loadReportData($reportFile, 0);
        $records = $parser->records;
        
        $payload = [];

        $associatedSkus =[];
        $errorSkus = [];

        foreach ($records as $record) {
            if (str_contains($record['SKU'], "-FBA-NEW")) {
                $shortStr = rtrim($record['SKU'], "-FBA-NEW");
                $formattedItem = new asinSku($record['(Child) ASIN'], $shortStr);
                array_push($payload, $formattedItem); 
            }
            
            if (str_contains($record['SKU'], "-FBA")) {
                // var_dump($record['SKU']);
                $shortStr = rtrim($record['SKU'], "-FBA");
                $formattedItem = new asinSku($record['(Child) ASIN'], $shortStr);
                array_push($payload, $formattedItem); 
            }
            
            if (str_contains($record['SKU'], "-MFN")) {
                $shortStr = rtrim($record['SKU'], "-MFN");
                $formattedItem = new asinSku($record['(Child) ASIN'], $shortStr);
                array_push($payload, $formattedItem); 
            }
            if (str_contains($record['SKU'], "-WET")) {
                $shortStr = rtrim($record['SKU'], "-WET");
                $formattedItem = new asinSku($record['(Child) ASIN'], $shortStr);
                array_push($payload, $formattedItem); 
            }
            if (!str_contains($record['SKU'], "-FBA") && !str_contains($record['SKU'], "-MFN") && !str_contains($record['SKU'], "-WET") && !str_contains($record['SKU'], "-FBA-NEW")) {
                $formattedItem = new asinSku($record['(Child) ASIN'], $record['SKU']);
                array_push($payload, $formattedItem); 
            }
           

        }



        
        foreach ($payload as $pay) {
            $item = $shopDB->getInventoryBySKU($pay->sku);
            if ($item && !str_contains($pay->sku, "-FBA-NEW")) {
                try {
                    $utilityDB->addAsinAssoc($pay->asin, $pay->sku);
                } catch (\Exception $ex) {
                    array_push($errorSkus, $pay);
                    continue;
                }
            } else {
                array_push($errorSkus, $pay);
            }
        }

        file_put_contents("asinSku.json", json_encode($payload));

        


       return [$associatedSkus, $errorSkus];
    }



    // public function listShipments($startDate, $endDate) {

    // }
}


?>