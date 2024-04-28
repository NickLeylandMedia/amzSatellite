<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

use DB;
use Exception;
use amzSatellite\Parser;
use amzSatellite\ShopDBConnection;
use amzSatellite\UtilDBConnection;


class Loader
{ 
    public function buildAsinAssoc($reportFile) {
        $parser = new Parser();
        $shopDB = new ShopDBConnection();
        $utilityDB = new UtilDBConnection();
        $parser->loadReportData($reportFile, 0);
        $records = $parser->records;
        $masterSkuList = $shopDB->getAllSkus();
        
        $amzSkus = [];



        foreach ($records as $record) {
            if (str_contains($record['SKU'], "-FBA")) {
                var_dump($record['SKU']);
            }
            
        }

       
        
        // foreach ($masterSkuList as $sku) {
        //     var_dump($sku);
        // }

       
    }



    // public function listShipments($startDate, $endDate) {

    // }
}


?>