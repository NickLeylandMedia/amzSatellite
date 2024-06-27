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



class Loader2
{
    public function buildAsinAssoc($reportFile)
    {
        $parser = new Parser();
        $shopDB = new ShopDBConnection();
        $utilityDB = new UtilDBConnection();
        $parser->loadReportData($reportFile, 0);
        $records = $parser->records;
        $dbSkus = $shopDB->getAllSkus();
        $recordSkus = [];
        $processErrors = [];

        $payload = [];
        $associatedSkus = [];
        $errorSkus = [];

        //Associate matching skus from db with asin, build payload for SQL processing.
        foreach ($records as $record) {
            $recSku = $record['SKU'];
            array_push($recordSkus, $recSku);
            if (in_array($recSku, $dbSkus)) {
                $formattedItem = new asinSku($record['(Child) ASIN'], $recSku);
                array_push($payload, $formattedItem);
            } else {
                array_push($errorSkus, $recSku);
            }
        }

        //Iterate all skus and if no match, add to errorskus.
        foreach ($dbSkus as $dbSku) {
            if (!in_array($dbSku, $recordSkus)) {
                array_push($errorSkus, $dbSku);
            }
        }

        //Iterate payload and create database entries.
        foreach ($payload as $item) {
            try {
                $utilityDB->addAsinAssoc($item->asin, $item->sku);
                array_push($associatedSkus, $item);
            } catch (\Exception $ex) {
                array_push($processErrors, $ex);
            }
        }

        var_dump($processErrors);

        return [
            'payload' => $payload,
            'associatedSkus' => $associatedSkus,
            'errorSkus' => $errorSkus,
            'processErrors' => $processErrors
        ];
    }
}
