<?php

declare(strict_types=1);

namespace amzSatellite\models;

class asinOffer
{
    public $sellerName;
    public $sellerID;
    public $minHandlingTime;
    public $maxHandlingTime;
    public $price;
    public $shippingCost;
    public $totalCost;
    public $asin;

    public function __construct($asin, $sellerName, $sellerID, $minHandlingTime, $maxHandlingTime, $price, $shippingCost, $totalCost)
    {
        $this->asin = $asin;
        $this->sellerName = $sellerName;
        $this->sellerID = $sellerID;
        $this->minHandlingTime = $minHandlingTime;
        $this->maxHandlingTime = $maxHandlingTime;
        $this->price = $price;
        $this->shippingCost = $shippingCost;
        $this->totalCost = $totalCost;
    }
}
