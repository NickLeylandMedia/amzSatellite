<?php

declare(strict_types=1);

namespace amzSatellite;

use League\Csv\Reader;
use League\Csv\Writer;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';


class MainParser
{
    public $reportLocation;

    public function loadReport($file_path)
    {
        if (!file_exists($file_path) || !is_readable($file_path)) {
            die("File not found or not readable");
        }
    }

    public function getRecordsFromReport()
    {
    }
}
