<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

use DB;

class Sellers
{
    public function __construct() {
        DB::$user = $_ENV["DB_USER"];
        DB::$password = $_ENV["DB_PASSWORD"];
        DB::$dbName = $_ENV["DB_NAME"];
    }

    public static function addSeller($name, $amz_seller_id, $city, $state, $zip, $address, $phone, $email, $website) {
        DB::insert('sellers', [
            'name' => $name,
            'amz_seller_id' => $amz_seller_id,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'website' => $website
        ]);
    }

    public static function getSellerByID($id) {
        $seller = DB::query("SELECT * FROM sellers WHERE amz_seller_id=%s", $id);
        return $seller;
    }

    public static function getSellerByName($name) {
        $seller = DB::query("SELECT * FROM sellers WHERE name=%s", $name);
        return $seller;
    }

    public static function listAllSellers() {
        $sellers = DB::query("SELECT * FROM sellers");
        return $sellers;
    }


}


?>