<?php

declare(strict_types=1);

namespace amzSatellite;

use League\Csv\Reader;
use League\Csv\Writer;



// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/models/skuSales.php';

require_once __DIR__ . '/models/skuOrdered.php';

require_once __DIR__ . '/models/shipmentSKU.php';


use Exception;
use skuSales;
use skuOrdered;
use shipmentSKU;

class Parser
{
    public $records = [];
    public $skuRecords = [];
    public $salesData = [];
    public $receivingData = [];
    public $shipmentData = [];


    public function loadReportData($filename, $headerOffset = 0, bool $saveFile = false) {
        if (!file_exists(__DIR__ . "/input/$filename")) {
            throw new Exception("File not found.");
        }
        //Use fgetcsv if filename includes ".tsv"
        if (strpos($filename, ".tsv") !== false) {
            $rowCount = 1;
            $file = fopen(__DIR__ . "/input/$filename", "r");
            $data = [];
            while (($line = fgetcsv($file, 0, "\t")) !== false) {
                if ($rowCount > $headerOffset) {
                    array_push($data, $line);
                }
                $rowCount++;
            }
            fclose($file);
            //Save to csv if enabled
            if ($saveFile) {
                $writer = Writer::createFromPath(__DIR__ . "/output/$filename", "w");
                $writer->insertAll($data);
            }
            return $this->records = $data;
        }
        //Use League\Csv\Reader if filename includes ".csv"
        if (strpos($filename, ".csv") !== false) {
            $reader = Reader::createFromPath(__DIR__ . "/input/$filename", "r");
            $reader->setHeaderOffset($headerOffset);
            $data = $reader->getRecords();

            //Save to csv if enabled
            if ($saveFile) {
                $writer = Writer::createFromPath(__DIR__ . "/output/$filename", "w");
                $writer->insertAll($data);
            }
            return $this->records = $data;
        }
    }

    public function bulkLoadReportData($filenames, $headerOffset = 8, bool $saveFile = false) {
        //Load report data from multiple files using league csv and merge them into one array
        $data = [];
        foreach ($filenames as $filename) {
            //Load report data for each individual report
            $records = $this->loadReportData($filename, $headerOffset);
            foreach ($records as $record) {
                array_push($data, $record);
            }
        }
        if ($saveFile) {
            $writer = Writer::createFromPath(__DIR__ . "./output/$filename", "w");
            $writer->insertAll($data);
        }
        return $this->records = $data;
    }

    public function loadSkuData(array $skus) {
        if (empty($this->records)) {
            throw new Exception("No records loaded, please load your data with loadReportData() or bulkLoadReportData first.");
        }
        foreach ($skus as $sku) {
            foreach ($this->records as $record) {
                if ($record["SKU"] == $sku) {
                    array_push($this->skuRecords, $record);
                }
            }
        }
    }

    public function listRecords() {
        if (empty($this->records)) {
            throw new Exception("No records loaded, please load your data with loadReportData() or bulkLoadReportData first.");
        }
        foreach ($this->records as $record) {
            var_dump($record);
        }
    }

    public function skuReceivedData() {
        if (empty($this->records)) {
            throw new Exception("No records loaded, please load your data with loadReportData() or bulkLoadReportData first.");
        }
        foreach ($this->records as $record) {
            if (!$record["SKU"] || !$record["Qty Ordered"]) {
                var_dump("SKU records are not in the correct format. Please ensure that the SKU records have the correct headers.");
                return;
            }
            $converted = new skuOrdered($record["SKU"], $record["Qty Ordered"]);
            var_dump($converted);
            array_push($this->receivingData, $converted);
        }
        return $this->receivingData;
    }

    public function amzSalesData() {
        if (empty($this->skuRecords)) {
            throw new Exception("No SKU records loaded, please load your data with loadSkuData() first.");
        }
        //Throw an error if the SKU records are not in the correct format
         if (!array_key_exists("Units Ordered", $this->skuRecords[0]) || !array_key_exists("Ordered Product Sales", $this->skuRecords[0])) {
            throw new Exception("SKU records are not in the correct format. Please ensure that the SKU records have the correct headers.");
        }
        //If the SKU records are in the correct format, convert them to the skuSales class
        foreach ($this->skuRecords as $record) {
            $converted = new skuSales($record["SKU"], $record["Units Ordered"], $record["Ordered Product Sales"]);
            array_push($this->salesData, $converted);
        }
        return $this->salesData;
    }

    public function amzShipmentData() {
        // Initialize variables
        $prePayload = [];
        $payload = [];
        // Check if records are loaded
        if (empty($this->records)) {
            throw new Exception("No records loaded, please load your data with loadReportData() or bulkLoadReportData first.");
        }
        // Convert records to shipmentSKU objects and store in prePayload array
        foreach ($this->records as $record) {
            $converted = new shipmentSKU($record[0], $record[2], $record[1], $record[9]);
            $prePayload[] = $converted;
        }
        // Combine quantities of objects with a matching SKU
        foreach ($prePayload as $item) {
            if (!array_key_exists($item->sku, $payload)) {
                $payload[$item->sku] = $item;
            } else {
                $payload[$item->sku]->quantity += $item->quantity;
            }
        }
        // Store the combined shipment data in shipmentData property
        $this->shipmentData = $payload;

        return $this->shipmentData;
    }

    public function extractSkus() {
        // Check if records are loaded
        if (empty($this->records)) {
            throw new Exception("No records loaded, please load your data with loadReportData() or bulkLoadReportData first.");
        }

        foreach ($this->records as $record) {
            var_dump($record['SKU']);
        }
    }

    
}



?>