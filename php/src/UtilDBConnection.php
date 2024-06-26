<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/models/costInfo.php';




use DB;
use costInfo;
use Dotenv\Dotenv;
use MeekroDB;

$dotenv = Dotenv::createImmutable(__DIR__, "/../.env")->load();

class UtilDBConnection
{
    private $conn;

    public function __construct()
    {
        $this->conn = new MeekroDB($_ENV["UTIL_DB_HOST"], $_ENV["UTIL_DB_USER"], $_ENV["UTIL_DB_PASSWORD"], $_ENV["UTIL_DB_NAME"]);
    }

    public function listSellers()
    {
        $results = $this->conn->query("SELECT * FROM sellers");
        return $results;
    }

    public function getSellerByID($id)
    {
        $results = $this->conn->query("SELECT * FROM sellers WHERE amz_seller_id=%s", $id);
        return $results;
    }

    public function addAsinAssoc($asin, $sku)
    {
        $this->conn->insert('product_asins', [
            'asin' => $asin,
            'sku' => $sku
        ]);
    }

    public function getASINAssocs()
    {
        $results = $this->conn->query("SELECT * FROM product_asins");
        return $results;
    }


    public function listEbaySessions()
    {
        $results = $this->conn->query("SELECT * FROM ebay_sessions");
        return $results;
    }

    public function addEbaySession($accessToken, $refreshToken, $expiration)
    {
        $this->conn->insert('ebay_sessions', [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'access_expiration' => $expiration
        ]);
    }
}
