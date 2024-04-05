<?php

declare(strict_types=1);


// Enabling Composer Packages
require __DIR__ . '/../../vendor/autoload.php';

class rateRequest {
    public $carrier;
    public $service;
    public $fromPostalCode;
    public $toPostalCode;
    public $weight;
    public $length;
    public $width;
    public $height;
    public $reqType = "Rates";

    public function __construct($carrier, $service, $fromPostalCode, $toPostalCode, $weight, $length, $width, $height)
    {
        $this->carrier = $carrier;
        $this->service = $service;
        $this->fromPostalCode = $fromPostalCode;
        $this->toPostalCode = $toPostalCode;
        $this->weight = $weight;
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->reqType = "Rates";
    }
}

?>