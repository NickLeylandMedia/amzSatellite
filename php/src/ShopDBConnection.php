<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/models/costInfo.php';




use DB;
use costInfo;
use MeekroDB;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__, "/../.env")->load();

class ShopDBConnection
{
    private $conn;

    public function __construct() {
        $this->conn = new MeekroDB($_ENV["SHOP_DB_HOST"], $_ENV["SHOP_DB_USER"], $_ENV["SHOP_DB_PASSWORD"], $_ENV["SHOP_DB_NAME"]);
    }

    public function getAllProducts() {
        $results = $this->conn->query("SELECT * FROM products");
        return $results;
    }

    public function getProductBySKU($sku) {
        $result = $this->conn->query("SELECT * FROM products WHERE sku = %s", $sku);
        return $result;
    }

    public function getInventoryBySKU($sku) {
        $result = $this->conn->query("SELECT * FROM inventory WHERE sku = %s", $sku);
        return $result;
    }

    public function getSKUCost($sku) {
        $result = $this->conn->query("SELECT * FROM inventory WHERE sku = %s", $sku);
        $conResult = new costInfo($result[0]['sku'], $result[0]['cost']);
        
        return $conResult;
    }

    public function getAllSkus(bool $save = false) {
        $skus = [];
        $results = $this->conn->query("SELECT sku FROM inventory");
        foreach ($results as $result) {
            $sku  = $result['sku'];
            array_push($skus, $sku);
        }
        $filtrate = array_filter($skus);
        $unique = array_unique($filtrate);

        if ($save) {
            file_put_contents("skus.json", json_encode($unique));
        }

        DB::disconnect();
        return $unique;

    }
}





?>