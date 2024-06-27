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

    public function __construct()
    {
        $this->conn = new MeekroDB($_ENV["SHOP_DB_HOST"], $_ENV["SHOP_DB_USER"], $_ENV["SHOP_DB_PASSWORD"], $_ENV["SHOP_DB_NAME"]);
    }

    public function getInventoryInfo()
    {
        $results = $this->conn->query("SELECT database_sync.products.title, database_sync.inventory.location_id, database_sync.inventory.sku, database_sync.inventory.available, variant_table.price, variant_table.barcode, database_sync.inventory.location_id
        FROM database_sync.products,
        JSON_TABLE(
            variants,
            '$[*]'
            COLUMNS(
                id VARCHAR(50) PATH '$.inventory_item_id',
                price VARCHAR(50) PATH '$.price',
                barcode VARCHAR(50) PATH '$.barcode'
            )
        ) AS variant_table JOIN database_sync.inventory on variant_table.id = database_sync.inventory.inventory_item_id");
        return $results;
    }

    public function getAllProducts()
    {
        $results = $this->conn->query("SELECT * FROM products");
        return $results;
    }

    public function getInventoryBySKU($sku)
    {
        $result = $this->conn->query("SELECT * FROM inventory WHERE sku = %s", $sku);
        return $result;
    }

    public function getSKUCost($sku)
    {
        $result = $this->conn->query("SELECT * FROM inventory WHERE sku = %s", $sku);
        $conResult = new costInfo($result[0]['sku'], $result[0]['cost']);

        return $conResult;
    }

    public function getAllSkus(bool $save = false)
    {
        $skus = [];
        $results = $this->conn->query("SELECT sku FROM inventory GROUP BY sku");
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
