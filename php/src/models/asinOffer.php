<?php

declare(strict_types=1);


// Enabling Composer Packages
require __DIR__ . '/../../vendor/autoload.php';

class asinOffer {
    public $sellerName;
    public $sellerID;
    public $minHandlingTime;
    public $maxHandlingTime;
    public $price;
    public $shippingCost;
    public $totalCost;


    
    public function __construct($sellerName, $sellerID, $minHandlingTime, $maxHandlingTime, $price, $shippingCost, $totalCost)
    {
        $this->sellerName = $sellerName;
        $this->sellerID = $sellerID;
        $this->minHandlingTime = $minHandlingTime;
        $this->maxHandlingTime = $maxHandlingTime;
        $this->price = $price;
        $this->shippingCost = $shippingCost;
        $this->totalCost = $totalCost;
    }
}

?>