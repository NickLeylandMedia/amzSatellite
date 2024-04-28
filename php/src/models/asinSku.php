<?php

declare(strict_types=1);


// Enabling Composer Packages
require __DIR__ . '/../../vendor/autoload.php';

class asinSku {
    public $asin;
    public $sku;
    
    
    public function __construct($asin, $sku)
    {
        $this->sku = $sku;
        $this->asin = $asin;
    }
   
}

?>