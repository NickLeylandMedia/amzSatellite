<?php

declare(strict_types=1);


// Enabling Composer Packages
require __DIR__ . '/../../vendor/autoload.php';

class skuOrdered {
    public $sku;
    public $unitsOrdered;
   
    public function __construct($sku, $unitsOrdered)
    {
        $this->sku = $sku;
        $this->unitsOrdered = $unitsOrdered;
    }
}

?>