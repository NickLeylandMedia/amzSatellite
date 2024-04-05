<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

use League\Csv\Reader;
use League\Csv\Writer;
use amzSatellite\APIConnection;



class Audit
{
    public static function audit(){
        $api = new APIConnection();
        $reader = Reader::createFromPath(__DIR__ . "/input/input.csv", "r");
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        
        foreach ($records as $record) {
            var_dump($record);
        }
        // var_dump($records);
    }
}





?>