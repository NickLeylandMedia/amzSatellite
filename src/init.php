<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

//Loading Packages/deps
use Dotenv\Dotenv;
use League\Csv\Reader;
use League\Csv\Writer;
use SellingPartnerApi\SellingPartnerApi;
use SellingPartnerApi\Enums\Endpoint;


// Get environment variables

// Define environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../.env");
$dotenv->load();



class APIConnection 
{
    private $api;
    private $pricing;
    private $fees;
    

    public function __construct()
    {   
        //Overall Connection
        $this-> api = SellingPartnerApi::make(
            clientId: $_ENV["CLIENT_ID"],
            clientSecret: $_ENV["CLIENT_SECRET"],
            refreshToken: $_ENV["CLIENT_SECRET"],
            endpoint: Endpoint::NA,  // Or Endpoint::EU, Endpoint::FE, Endpoint::NA_SANDBOX, etc.
        )->seller();

        //API Endpoints
        $this->pricing = $this->api->productPricingV0();
        $this->fees = $this->api->productFees();
    }

    public function getOffersByASIN(string $asin, string $marketplace_id = 'ATVPDKIKX0DER', string $item_condition = 'New', string $customerType)
    {
        $response = $this->pricing->getItemOffers($asin, $marketplace_id, $item_condition);
        return $response;
    }
   

    public function echoVars()
    {
        echo $_ENV["REFRESH_TOKEN"];
        echo $_ENV["CLIENT_ID"];
        echo $_ENV["CLIENT_SECRET"];
    }

}










?>
