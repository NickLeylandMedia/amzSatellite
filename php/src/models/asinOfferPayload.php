<?php

declare(strict_types=1);


// Enabling Composer Packages
require __DIR__ . '/../../vendor/autoload.php';

class asinOfferPayload
{
    public $asin;
    public $offers;

    public function __construct($asin, $offers)
    {
        $this->asin = $asin;
        $this->offers = $offers;
    }
}
