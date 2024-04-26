<?php

declare(strict_types=1);


// Enabling Composer Packages
require __DIR__ . '/../../vendor/autoload.php';

class skuSales {
    public $sku;
    public $unitSales;
    public $numberSales;
   
    public function __construct($sku, $unitSales, $numberSales)
    {
        $this->sku = $sku;
        $this->unitSales = $unitSales;
        $this->numberSales = $numberSales;
    }
}

?>