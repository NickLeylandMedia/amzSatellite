<?php

declare(strict_types=1);


// Enabling Composer Packages
require __DIR__ . '/../../vendor/autoload.php';

class costInfo {
    public $sku;
    public $cost;
    
    public function __construct($sku, $cost)
    {
        $this->sku = $sku;
        $this->cost = $cost;
    }
   
}

?>