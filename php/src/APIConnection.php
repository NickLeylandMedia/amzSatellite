<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/models/asinOffer.php';

//Loading Packages/deps
use Dotenv\Dotenv;
use League\Csv\Reader;
use League\Csv\Writer;
use SellingPartnerApi\SellingPartnerApi;
use SellingPartnerApi\Enums\Endpoint;

use AmazonPHP\SellingPartner\AccessToken;
use AmazonPHP\SellingPartner\Regions;
use AmazonPHP\SellingPartner\SellingPartnerSDK;
use Buzz\Client\Curl;
use AmazonPHP\SellingPartner\Configuration;

//Notification Models
use AmazonPHP\SellingPartner\Model\Notifications\CreateDestinationRequest;
use AmazonPHP\SellingPartner\Model\Notifications\DestinationResource;


//Product Fees Models
use AmazonPHP\SellingPartner\Model\ProductFees\FeesEstimateByIdRequest;
use AmazonPHP\SellingPartner\Model\ProductFees\FeesEstimateRequest;  
use AmazonPHP\SellingPartner\Model\ProductFees\MoneyType;
use AmazonPHP\SellingPartner\Model\ProductFees\PriceToEstimateFees;

use amzSatellite\Sellers;
use asinOffer;

// Get environment variables

// Define environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../.env")->load();

class APIConnection 
{
    private $api;
    private $pricing;
    private $fees;
    private $catalog;
    private $orders;
    private $notifications;
    
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
        $this->catalog = $this->api->catalogItems();
        $this->orders = $this->api->orders();
        $this->notifications = $this->api->notifications();
    }

    public function getDimensionsByASIN($asin) {
        $rawResponse = $this->catalog->getCatalogItem($asin, ["ATVPDKIKX0DER"], ["dimensions"])->json();
        $response = $rawResponse['dimensions'][0];
        $itemDims = $response["item"];
        $packageDims = $response["package"];
        return ['item' => $itemDims, 'package' => $packageDims];
    }

    public function getOrders($startDate, $endDate, $nextToken = null, $maxResults = 10) {
        $this->orders->getOrders(["ATVPDKIKX0DER"], $startDate, $endDate, $endDate, $startDate, ["Unshipped", "Shipped"], null, null, null, null, $maxResults, null, null, $nextToken, null, null, null, null, null, null, null, null);
    }

    public function getOrderByID($orderId) {
        $response = $this->orders->getOrder($orderId, ["ATVPDKIKX0DER"])->json();
        return $response;
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

    public function getFeesEstimate(string $asin, string $marketplace_id = 'ATVPDKIKX0DER', bool $is_amazon_fulfilled, string $currency_code, $id_value, $id_type, $price, $shipping_price)
    {
            $payload = new FeesEstimateByIdRequest([
                    'fees_estimate_request' => new FeesEstimateRequest([
                        "marketplace_id" => $marketplace_id,
                        "is_amazon_fulfilled" => $is_amazon_fulfilled,
                        "price_to_estimate_fees" => new PriceToEstimateFees([
                            'listing_price' => new MoneyType([
                                "currency_code" => $currency_code,
                                "amount" => $price
                            ]),
                            'shipping' => new MoneyType([
                                "currency_code" => $currency_code,
                                "amount" => $shipping_price
                            ])
                        ]),
                        'identifier' => uniqid("TR-", true)
                    ]),
                    'id_type' => $id_type,
                    'id_value' => $id_value
                ]);
                return $payload;   
    }

    public function getOffersByASIN($asin) {
        $offers = [];
        $response = $this->pricing->getItemOffers($asin, 'ATVPDKIKX0DER', 'New', 'Consumer')->json();
        $data = $response['payload']['Offers'];
        $sellerInfo = new Sellers();

        foreach ($data as $offer) {
            $seller = $sellerInfo::getSellerByID($offer["SellerId"]);
            $convertedOffer = new asinOffer($seller[0]["name"], $offer['SellerId'], $offer["ShippingTime"]["minimumHours"], $offer["ShippingTime"]["maximumHours"], $offer["ListingPrice"]["Amount"], $offer["Shipping"]["Amount"], $offer["ListingPrice"]["Amount"] + $offer["Shipping"]["Amount"]);
            array_push($offers, $convertedOffer);
        }
        return $offers;
    }


}


?>
