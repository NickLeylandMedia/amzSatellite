<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

use DB;
use amzSatellite\Parser;


class Loader
{
    public function __construct() {
        DB::$user = $_ENV["DB_USER"];
        DB::$password = $_ENV["DB_PASSWORD"];
        DB::$dbName = $_ENV["DB_NAME"];
    }

    public function buildAsinAssoc() {
        $parser = new Parser();
        $parser->loadReportData("report.csv", 0);
        $records = $parser->records;
        foreach ($records as $record) {
            
        }
    }



    // public function listShipments($startDate, $endDate) {

    // }
}


?>