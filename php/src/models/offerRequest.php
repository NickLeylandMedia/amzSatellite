<?php

declare(strict_types=1);


// Enabling Composer Packages
require __DIR__ . '/../../vendor/autoload.php';

class offerRequest {
    public $asin;
    public $marketplace;
    public $condition;
    public $customerType;
    public $reqType = "Offers";

    public function __construct($asin, $marketplace, $condition, $customerType, $reqType)
    {
        $this->asin = $asin;
        $this->marketplace = $marketplace;
        $this->condition = $condition;
        $this->customerType = $customerType;
        $this->reqType = $reqType;
    }
}

?>