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
    private $catalog;
    
    

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
        $this->catalog = $this->api->catalogItems();
    }

    public function searchCatalogItems($query, $pageToken = null) {
        if (is_array($query)) {
            $response = $this->catalog->searchCatalogItems(["ATVPDKIKX0DER"], null, null, null, null, null, $query, null, null, 10, $pageToken)->json();
            return $response;
        }

        if (!is_array($query)) {
            $response = $this->catalog->searchCatalogItems(["ATVPDKIKX0DER"], null, null, null, null, null, [$query], null, null, 10, $pageToken)->json();
            return $response;
        }
    }

    public function bigCatalogSearch($query, $pages) {
        $response = null;

        $page = 1;

        $brands = [];
        $items = [];
        
        if (is_array($query)) {
            $response = $this->catalog->searchCatalogItems(["ATVPDKIKX0DER"], null, null, null, null, null, $query, null, null, 10, null)->json();
            $results = $response['items'];
            $brandResults = $response['refinements']['brands'];
            foreach ($brandResults as $brand) {
                array_push($brands, $brand['brandName']);
            }
            foreach ($results as $result) {
                array_push($items, $result);
            }
            while ($page < $pages) {
                $page++;
                echo $page;
                $response = $this->catalog->searchCatalogItems(["ATVPDKIKX0DER"], null, null, null, null, null, $query, null, null, 10, $response['pagination']['nextToken'])->json();
                $results = $response['items'];
                $brandResults = $response['refinements']['brands'];
                foreach ($brandResults as $brand) {
                    array_push($brands, $brand['brandName']);
                }
                foreach ($results as $result) {
                    array_push($items, $result);
                }
            }
        }

        if (!is_array($query)) {
            $response = $this->catalog->searchCatalogItems(["ATVPDKIKX0DER"], null, null, null, null, null, [$query], null, null, 10, null)->json();
            $results = $response['items'];
            $brandResults = $response['refinements']['brands'];
            foreach ($brandResults as $brand) {
                array_push($brands, $brand['brandName']);
            }
            foreach ($results as $result) {
                array_push($items, $result);
            }
            while ($page < $pages) {
                $page++;
                echo $page;
                $response = $this->catalog->searchCatalogItems(["ATVPDKIKX0DER"], null, null, null, null, null, [$query], null, null, 10, $response['pagination']['nextToken'])->json();
                $results = $response['items'];
                $brandResults = $response['refinements']['brands'];
                foreach ($brandResults as $brand) {
                    array_push($brands, $brand['brandName']);
                }
                foreach ($results as $result) {
                    array_push($items, $result);
                }
            }
            
        }

        return ['items' => $items, 'brands' => array_unique($brands)];


    }

    public function getOffersByASIN(string $asin, string $marketplace_id = 'ATVPDKIKX0DER', string $item_condition = 'New', string $customerType)
    {
        $response = $this->pricing->getItemOffers($asin, $marketplace_id, $item_condition)->json();
        return $response;
    }

    public function getFeesEstimate(string $asin, string $marketplace_id = 'ATVPDKIKX0DER', string $item_condition = 'New', string $price)
    {
        // $response = $this->fees->getMyFeesEstimateForAsin();
        // return $response;
    }

    public function getReport($reportId)
    {
        $response = $this->reports->getReport($reportId);
        return $response;
    }

    public function getReportDocument($docId)
    {
        $response = $this->reports->getReportDocument($docId, "GET_MERCHANT_LISTINGS_ALL_DATA", false);
        return $response;
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
