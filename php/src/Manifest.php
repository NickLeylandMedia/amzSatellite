<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

use DB;
use amzSatellite\Formatter;


class Manifest
{
    public function __construct() {
        DB::$user = $_ENV["DB_USER"];
        DB::$password = $_ENV["DB_PASSWORD"];
        DB::$dbName = $_ENV["DB_NAME"];
    }

    public function getShipments() {
        $items = DB::query("SELECT * FROM shipments");
        return $items;
    }

    public function createShipment(string $date, string $name, string $summary) {
        $formattedDate = Formatter::stringToDBDate($date);
        DB::insert('shipments', [
            'date' => $formattedDate,
            'name' => $name,
            'summary' => $summary
        ]);
    }

    public function addToShipment(int $shipmentId, string $sku, int $quantity) {
        DB::insert('shipment_skus', [
            'shipment_id' => $shipmentId,
            'sku' => $sku,
            'quantity' => $quantity
        ]);
    }

    public function bulkAddToShipment(int $shipmentId, array $items) {
        foreach ($items as $item) {
            $this->addToShipment($shipmentId, $item['sku'], $item['quantity']);
        }
    }



    // public function listShipments($startDate, $endDate) {

    // }
}


?>