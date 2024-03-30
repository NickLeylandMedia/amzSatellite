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
use SellingPartnerApi\Seller\ReportsV20210630\Dto\CreateReportSpecification;


// Get environment variables

// Define environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../.env")->load();

class APIConnection 
{
    private $api;
    private $pricing;
    private $fees;
    private $reports;
    

    public function __construct()
    {   
        //Overall Connection
        $this-> api = SellingPartnerApi::make(
            clientId: $_ENV["CLIENT_ID"],
            clientSecret: $_ENV["CLIENT_SECRET"],
            refreshToken: $_ENV["REFRESH_TOKEN"],
            endpoint: Endpoint::NA,  // Or Endpoint::EU, Endpoint::FE, Endpoint::NA_SANDBOX, etc.
        )->seller();

        //API Endpoints
        $this->pricing = $this->api->productPricingV0();
        $this->fees = $this->api->productFees();
        $this->reports = $this->api->reports();
    }

    public function getOffersByASIN(string $asin, string $marketplace_id = 'ATVPDKIKX0DER', string $item_condition = 'New', string $customerType)
    {
        $response = $this->pricing->getItemOffers($asin, $marketplace_id, $item_condition);
        return $response;
    }

    public function getFeesEstimate(string $asin, string $marketplace_id = 'ATVPDKIKX0DER', string $item_condition = 'New', string $price)
    {
        // $response = $this->fees->getMyFeesEstimateForAsin();
        // return $response;
    }
   

    public function echoVars()
    {
        echo $_ENV["REFRESH_TOKEN"];
        echo $_ENV["CLIENT_ID"];
        echo $_ENV["CLIENT_SECRET"];
    }

    public function listReports($reportType)
    {
        switch ($reportType) {
            case 'allListings':
                $response = $this->reports->getReports(['GET_MERCHANT_LISTINGS_ALL_DATA'])->json();
                return $response;
                break;   
        }
    }

    public function createReports($reportType)
    {
        switch ($reportType) {
            case 'allListings':
                $payload = new CreateReportSpecification(
                    reportType: 'GET_MERCHANT_LISTINGS_ALL_DATA',
                    marketplaceIds: ['ATVPDKIKX0DER'],
                    dataStartTime: new \DateTime(),
                    dataEndTime: new \DateTime()
                );
                $arr = $this->reports->createReport($payload)->json();
                $value = reset($arr);
                return $value;
                break;
            
        }


        
    }

}


?>
