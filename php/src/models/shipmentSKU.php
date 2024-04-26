<?php

declare(strict_types=1);


// Enabling Composer Packages
require __DIR__ . '/../../vendor/autoload.php';

class shipmentSKU {
    public $sku;
    public $asin;
    public $title;
    public $quantity;
    
    public function __construct($sku, $asin, $title, $quantity)
    {
        $this->sku = $sku;
        $this->asin = $asin;
        $this->title = $title;
        $this->quantity = $quantity;
    }
   
}

?>