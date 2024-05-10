<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

use DB;
use amzSatellite\Formatter;
use amzSatellite\Parser;
use Dotenv\Dotenv;

// Define environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../.env")->load();


class Manifest
{
    public function __construct()
    {
        DB::$host = $_ENV["UTIL_DB_HOST"];
        DB::$user = $_ENV["UTIL_DB_USER"];
        DB::$password = $_ENV["UTIL_DB_PASSWORD"];
        DB::$dbName = $_ENV["UTIL_DB_NAME"];
    }

    public function listShipments()
    {
        $items = DB::query("SELECT * FROM shipments");
        return $items;
    }

    public function getMostRecentShipment()
    {
        $shipment = DB::queryFirstRow("SELECT * FROM shipments ORDER BY date DESC LIMIT 1");
        return $shipment;
    }

    public function createShipment(string $date, string $name, string $summary)
    {
        $formattedDate = Formatter::stringToDBDate($date);
        DB::insert('shipments', [
            'date' => $formattedDate,
            'name' => $name,
            'summary' => $summary,
            'unique_id' => uniqid("", true),
        ]);
    }

    public function updateShipment(int $shipmentId, string $date, string $name, string $summary)
    {
        $formattedDate = Formatter::stringToDBDate($date);
        DB::update('shipments', [
            'date' => $formattedDate,
            'name' => $name,
            'summary' => $summary
        ], 'id=%i', $shipmentId);
    }

    public function deleteShipment(int $shipmentId)
    {
        DB::delete('shipments', 'id=%i', $shipmentId);
    }

    public function deleteShipmentByUUID(string $uuid)
    {
        DB::delete('shipments', 'uuid=%s', $uuid);
    }

    public function addToShipment(int $shipmentId, string $sku, int $quantity)
    {
        DB::insert('shipment_skus', [
            'shipment_id' => $shipmentId,
            'sku' => $sku,
            'quantity' => $quantity
        ]);
    }

    public function bulkAddToShipment(int $shipmentId, array $files)
    {
        $parser = new Parser();
        $parser->bulkLoadReportData($files, 8, false);
        $parser->amzShipmentData();

        foreach ($parser->shipmentData as $shipmentSKU) {
            DB::insert('shipment_skus', [
                'shipment_id' => $shipmentId,
                'sku' => $shipmentSKU->sku,
                'quantity' => $shipmentSKU->quantity
            ]);
        }

        return true;
    }



    // public function listShipments($startDate, $endDate) {

    // }
}
