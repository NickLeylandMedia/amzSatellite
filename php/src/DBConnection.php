<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/models/costInfo.php';


use DB;
use costInfo;


class DBConnection
{
    

    public function __construct() {
        DB::$host = $_ENV["DB_HOST"];
        DB::$user = $_ENV["DB_USER"];
        DB::$password = $_ENV["DB_PASSWORD"];
        DB::$dbName = $_ENV["DB_NAME"];
    }

    public static function getAllProducts() {
        $results = DB::query("SELECT * FROM products");
        return $results;
    }

    public static function getSKUCost($sku) {
        $result = DB::query("SELECT * FROM inventory WHERE sku = %s", $sku);
        $conResult = new costInfo($result[0]['sku'], $result[0]['cost']);
        return $conResult;
    }
}





?>