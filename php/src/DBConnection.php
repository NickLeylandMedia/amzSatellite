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

    public static function getProductBySKU($sku) {
        $result = DB::query("SELECT * FROM products WHERE sku = %s", $sku);
        return $result;
    }

    public static function getSKUCost($sku) {
        $result = DB::query("SELECT * FROM inventory WHERE sku = %s", $sku);
        $conResult = new costInfo($result[0]['sku'], $result[0]['cost']);
        return $conResult;
    }

    public static function getAllSkus(bool $save = false) {
        $skus = [];
        $results = DB::query("SELECT sku FROM inventory");
        foreach ($results as $result) {
            $sku  = $result['sku'];
            array_push($skus, $sku);
        }

        $filtrate = array_filter($skus);
        $unique = array_unique($filtrate);

        if ($save) {
            file_put_contents("skus.json", json_encode($unique));
        }
    }
}





?>