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
use SellingPartnerApi\Seller\ProductFeesV0\Dto\GetMyFeesEstimateRequest;
use SellingPartnerApi\Seller\ReportsV20210630\Dto\CreateReportSpecification;
use SellingPartnerApi\Seller\ProductFeesV0\Dto\FeesEstimateRequest;
use SellingPartnerApi\Seller\ProductFeesV0\Dto\PriceToEstimateFees;
use SellingPartnerApi\Seller\ProductFeesV0\Dto\MoneyType;
use SellingPartnerApi\Seller\ProductFeesV0\Dto\FeesEstimateByIdRequest;


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
    private $orders;
    private $fba;
    private $fbaInv;
    private $fbaOther;
    
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
        $this->orders = $this->api->orders();
        $this->fba = $this->api->fbaInbound();
        $this->fbaInv = $this->api->fbaInventory();
        $this->fbaOther = $this->api->fbaInventoryV1();
    }

    public function getDimensionsByASIN($asin) {
        $response = $this->catalog->getCatalogItem($asin, ["ATVPDKIKX0DER"], ["dimensions"])->json();
        return $response;
    }

    public function scopeFBA() {
        $response = $this->fbaInv->getInventorySummaries("Marketplace", "ATVPDKIKX0DER", ["ATVPDKIKX0DER"], false, null, null)->json();
        return $response;
    }

    public function getOrders($startDate, $endDate, $nextToken = null, $maxResults = 10) {
        $this->orders->getOrders(["ATVPDKIKX0DER"], $startDate, $endDate, $endDate, $startDate, ["Unshipped", "Shipped"], null, null, null, null, $maxResults, null, null, $nextToken, null, null, null, null, null, null, null, null);
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

    public function getOffersByASIN(string $asin, string $item_condition = 'New', string $customerType = "Consumer")
    {
        $response = $this->pricing->getItemOffers($asin, 'ATVPDKIKX0DER', $item_condition, $customerType)->json();
        return $response["payload"]["Summary"];
    }

    public function bulkFeesEstimate($asin, $price, $shipping) {
        $money = new MoneyType("USD", $price);
        $price = new PriceToEstimateFees($money);
        $request = new FeesEstimateRequest('ATVPDKIKX0DER', $price, $asin, false);
        $reqFormat = new FeesEstimateByIdRequest('asin', $asin, $request);

        // var_dump($money);

        // var_dump($price);

        // var_dump($request);

        // var_dump($reqFormat);

        $response = $this->fees->getMyFeesEstimates([$reqFormat]);
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
