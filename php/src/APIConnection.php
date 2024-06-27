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
use SellingPartnerApi\Seller\ProductFeesV0\Dto\PriceToEstimateFees;
use SellingPartnerApi\Seller\ProductFeesV0\Dto\GetMyFeesEstimateRequest;
use SellingPartnerApi\Seller\ProductFeesV0\Dto\FeesEstimateRequest;
use SellingPartnerApi\Seller\ProductFeesV0\Dto\MoneyType;


use amzSatellite\UtilDBConnection;

// use amzSatellite\Sellers;
use asinOffer;
use Exception;

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
        $this->api = SellingPartnerApi::seller(
            clientId: $_ENV["CLIENT_ID"],
            clientSecret: $_ENV["CLIENT_SECRET"],
            refreshToken: $_ENV["REFRESH_TOKEN"],
            endpoint: Endpoint::NA,  // Or Endpoint::EU, Endpoint::FE, Endpoint::NA_SANDBOX, etc.
        );

        //API Endpoints
        $this->pricing = $this->api->productPricingV0();
        $this->fees = $this->api->productFeesV0();
        $this->catalog = $this->api->catalogItemsV20220401();
        $this->orders = $this->api->ordersV0();
    }

    public function getOffersBasic($asin)
    {
        try {
            $response = $this->pricing->getItemOffers($asin, 'ATVPDKIKX0DER', 'New', 'Consumer')->json();
            return $response;
        } catch (Exception $ex) {
            $errmsg = $ex->getMessage();
            if (str_contains($errmsg, "invalid")) {
                var_dump("Invalid ASIN");
            }
            var_dump($errmsg);
        }
    }

    public function getDimensionsByASIN($asin)
    {
        $rawResponse = $this->catalog->getCatalogItem($asin, ["ATVPDKIKX0DER"], ["dimensions"])->json();
        $response = $rawResponse['dimensions'][0];
        $itemDims = $response["item"];
        $packageDims = $response["package"];
        return ['item' => $itemDims, 'package' => $packageDims];
    }

    public function getOrders($startDate, $endDate, $nextToken = null, $maxResults = 10)
    {
        $this->orders->getOrders(["ATVPDKIKX0DER"], $startDate, $endDate, $endDate, $startDate, ["Unshipped", "Shipped"], null, null, null, null, $maxResults, null, null, $nextToken, null, null, null, null, null, null, null, null);
    }

    public function getOrderByID($orderId)
    {
        $response = $this->orders->getOrder($orderId, ["ATVPDKIKX0DER"])->json();
        return $response;
    }

    public function searchCatalogItems($query, $pageToken = null)
    {
        if (is_array($query)) {
            $response = $this->catalog->searchCatalogItems(["ATVPDKIKX0DER"], null, null, null, null, null, $query, null, null, 10, $pageToken)->json();
            return $response;
        }

        if (!is_array($query)) {
            $response = $this->catalog->searchCatalogItems(["ATVPDKIKX0DER"], null, null, null, null, null, [$query], null, null, 10, $pageToken)->json();
            return $response;
        }
    }


    public function bigCatalogSearch($query, $pages)
    {
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

    public function getFeesByASIN($asin, $price)
    {
        $MT = new MoneyType("USD", $price);
        $PTEF = new PriceToEstimateFees($MT, null, null);
        $FER = new FeesEstimateRequest("ATVPDKIKX0DER", $PTEF, $asin);
        $GMFER = new GetMyFeesEstimateRequest($FER);

        $response = $this->fees->getMyFeesEstimateForAsin($asin, $GMFER)->json();
        return $response;
    }

    public function getOffersByASIN($asin, $maxRetries = 15)
    {
        //Initialize retries
        $retries = 0;
        do {
            try {
                //Array to store offers
                $offers = [];
                //Get offers from API
                $response = $this->pricing->getItemOffers($asin, 'ATVPDKIKX0DER', 'New', 'Consumer')->json();
                //If the response has a payload and offers, store the offers in a variable
                if (isset($response['payload'], $response['payload']['Offers']) && count($response['payload']['Offers']) > 0) {
                    $data = $response['payload']['Offers'];
                } else {
                    throw new Exception("No offers found for $asin.");
                }
                //Initialize DB Connection
                $sellerInfo = new UtilDBConnection();
                //Iterate through offers and convert them to asinOffer objects
                if (count($data) > 0) {
                    foreach ($data as $offer) {
                        //Retrieve Seller info from DB
                        $seller = $sellerInfo->getSellerByID($offer["SellerId"]);
                        //If the seller info is valid, create an asinOffer object
                        if (count($seller) > 0 && isset($seller[0]["name"], $offer["ShippingTime"]["minimumHours"], $offer["ShippingTime"]["maximumHours"], $offer["ListingPrice"]["Amount"], $offer["Shipping"]["Amount"])) {
                            $convertedOffer = new asinOffer($asin, $seller[0]["name"], $offer['SellerId'], $offer["ShippingTime"]["minimumHours"], $offer["ShippingTime"]["maximumHours"], $offer["ListingPrice"]["Amount"], $offer["Shipping"]["Amount"], $offer["ListingPrice"]["Amount"] + $offer["Shipping"]["Amount"]);
                        }
                        //If no seller is found, create an asinOffer object with the seller name as "Unknown"
                        if (count($seller) == 0) {
                            $convertedOffer = new asinOffer($asin, "Unknown", $offer['SellerId'], $offer["ShippingTime"]["minimumHours"], $offer["ShippingTime"]["maximumHours"], $offer["ListingPrice"]["Amount"], $offer["Shipping"]["Amount"], $offer["ListingPrice"]["Amount"] + $offer["Shipping"]["Amount"]);
                        }
                        //Push the offer to the offers array
                        array_push($offers, $convertedOffer);
                    }
                    return $offers;
                }
            } catch (\Exception $ex) {
                //If error message contains "No offers", throw an exception, end the retry loop
                if (str_contains($ex->getMessage(), "No offers")) {
                    throw new Exception("No offers found for $asin.");
                    return;
                }
                //If error message contains "invalid", throw an exception, end the retry loop
                if (str_contains($ex->getMessage(), "invalid")) {
                    throw new Exception("Invalid ASIN");
                    return;
                }
                //Increment retries on failure
                $retries++;
                //Sleep for 2 seconds before retrying
                sleep(2 * $retries);
                //If the max retries are reached, throw an exception
                if ($retries == $maxRetries) {
                    throw new Exception("Failed to get offers for $asin after $maxRetries retries.");
                }
            }
        } while ($retries < $maxRetries);
    }

    public function bulkGetOffersByASIN($asins)
    {
        //Initialize payload and error arrays
        $payload = [];
        $errorLoad = [];
        $count = 0;
        //Iterate through list of ASIN's and get offers
        foreach ($asins as $asin) {
            try {
                $count++;
                echo ("Processing ASIN $count of " . count($asins));
                //Get offers by ASIN
                $data = $this->getOffersByASIN($asin);
                //Push the ASIN and offers to the payload array
                array_push($payload, ["asin" => $asin, "offers" => $data]);
            } catch (\Exception $ex) {
                //If an error occurs, push the ASIN and error message to the errorLoad array
                array_push($errorLoad, ["asin" => $asin, "error" => $ex->getMessage()]);
            }
        }
        //Return final payload
        return [$payload, $errorLoad];
    }
}
