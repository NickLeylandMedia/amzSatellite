<?php

declare(strict_types=1);

namespace Blackhalloutfitters;

use AmazonPHP\SellingPartner\AccessToken;
use AmazonPHP\SellingPartner\Regions;
use AmazonPHP\SellingPartner\SellingPartnerSDK;
use Buzz\Client\Curl;
use AmazonPHP\SellingPartner\Configuration;
//Product Fees Models
use AmazonPHP\SellingPartner\Model\ProductFees\FeesEstimateByIdRequest;
use AmazonPHP\SellingPartner\Model\ProductFees\FeesEstimateRequest;
use AmazonPHP\SellingPartner\Model\ProductFees\FeesEstimateResult;


//  
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;
use AmazonPHP\SellingPartner\Model\ProductFees\IdType;
use AmazonPHP\SellingPartner\Model\ProductFees\MoneyType;
use AmazonPHP\SellingPartner\Model\ProductFees\PriceToEstimateFees;


require_once dirname(__DIR__, 1) . '\vendor\autoload.php';

class RequestHandler
{

    private Psr17Factory $factory;
    private Curl $client;
    private Configuration $configuration;
    private SellingPartnerSDK $sdk;
    private AccessToken $accessToken;
    private array $requests = [];
    private Logger $logger;

    final public const ALL = 'all';
    final public const TOTALFEES = 'total';
    final public const FEEDETAILS = 'details';

    public function __construct(string $refreshToken, string $clientId, string $clientIdSecret, Logger $logger = null)
    {
        $this->factory = new Psr17Factory();
        $this->client = new Curl($this->factory);

        $this->configuration = Configuration::forIAMUser(
            $clientId,
            $clientIdSecret,
            '', //These parameters appear to be unused, leaving blank for now.
            ''
        );

        if($logger == null){
            $this->logger = new Logger('RequestHandler');
            $this->logger->pushHandler(new StreamHandler(__DIR__ . '/sp-api-php.log'));
        }else{
            $this->logger = $logger;
        }

        $this->sdk = SellingPartnerSDK::create($this->client, $this->factory, $this->factory, $this->configuration, $this->logger);
        $this->accessToken = $this->sdk->oAuth()->exchangeRefreshToken($refreshToken);

    }

    /**
    * Creates a fee estimate request from an ID. The request will not be submitted until submitFeeEstimateRequests is called. 
    *
    * @param float $listing_price_amount The price you would like to reference for fee calculations. (Required)
    * @param string $id_value The ID to locate product. Must be an ASIN or SKU depending on the value of $id_type. (Required)
    * @param string $marketplace_id The marketplace ID for the market you would like to calculate fees for. (Optional, defaults to ATVPDKIKX0DER)
    * @param bool $is_amazon_fulfilled Whether or not you are looking for the FBA fees or merchant fulfilled fees. (Optional, defaults to false)
    * @param string $currency_code The currency code for the lisitng and shipping prices. (Optional, defaults to USD)
    * @param float $shipping_price_amount The shipping value charged to the customer you would like to reference for fee calculations (Optional, defaults to 0.00)
    * @param string $id_type The type of ID provided to locate product. Can be IdType::ASIN (ASIN) or IdType::SellerSKU (SKU) (Optional, defaults to IdType::ASIN)
    *
    * @return bool true if the request was created successfully, false otherwise.
    */

    public function createFeeEstimateRequest(float $listing_price_amount, string $id_value, string $marketplace_id = 'ATVPDKIKX0DER', bool $is_amazon_fulfilled = false, string $currency_code = "USD", float $shipping_price_amount = 0.00, string $id_type = IdType::ASIN): bool{
        try{
            if($this->getRequestsCount() <= 20){
                array_push($this->requests, new FeesEstimateByIdRequest([
                    'fees_estimate_request' => new FeesEstimateRequest([
                        "marketplace_id" => $marketplace_id,
                        "is_amazon_fulfilled" => $is_amazon_fulfilled,
                        "price_to_estimate_fees" => new PriceToEstimateFees([
                            'listing_price' => new MoneyType([
                                "currency_code" => $currency_code,
                                "amount" => $listing_price_amount
                            ]),
                            'shipping' => new MoneyType([
                                "currency_code" => $currency_code,
                                "amount" => $shipping_price_amount
                            ])
                        ]),
                        'identifier' => uniqid("TR-", true)
                    ]),
                    'id_type' => $id_type,
                    'id_value' => $id_value
                ]));
                return true;
            }else{
                throw new \Exception("Only 20 requests allowed for a given transaction. Submit the currently queued requests or run resetRequests() to reset the queue.");
            }
            
        }catch(\Exception $exception){
            $this->logger->error($exception->getMessage());
            return false;
        }
    }

    public function getOffersByASIN(string $asin){
        $endpoint = "https://sellingpartnerapi-na.amazon.com/products/pricing/v0/items/{$asin}/offers";

        $body = array(
            'MarketplaceId' => "ATVPDKIKX0DER",
            'CustomerType' => "Consumer",
            'ItemCondition' => "New"
        );

        $token = $this->accessToken->token();


        
      
       
    
        try {
        // Initialize cURL session
        $ch = curl_init();
        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            "Authorization: Bearer {$token}",
            "x-amz-security-token: {$token}"
        ));

        // echo $token;

        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        // Execute cURL request
        $response = curl_exec($ch);
        // Close cURL session
        curl_close($ch);
         // Decode and print the response
        var_dump($response);

       } catch(\Exception $exception){
        $this->logger->error($exception->getMessage());
        return false;
    }
        // try {
        //     if($this->getRequestsCount() <= 20){
                
        //         return true;
        //     }else{
        //         throw new \Exception("Only 20 requests allowed for a given transaction. Submit the currently queued requests or run resetRequests() to reset the queue.");
        //     }
        // } catch (\Exception $exception) {
        //     $this->logger->error($exception->getMessage());
        //     return false;
        // }
    }

    /**
     * Deletes all queued requests.
     *
     *
     * @return bool true if the request was created successfully, false otherwise.
     */

    public function resetRequests(): bool{
        $this->requests = [];
        return true;
    }

    /**
     * Returns a count of queued requests.
     *
     *
     * @return int the number of requests queued.
     */

     public function getRequestsCount(): int {
        return count($this->requests);
    }

    /**
     * Submits all queued requests and returns results.
     *
     * @param string $region The region you would like to calculate fees in. (Optional, defaults to Regions::NORTH_AMERICA) 
     * @param string $output How you would like the fee data returned. Valid values are RequestHandler::ALL (the raw output), RequestHandler::TOTALFEES (just the total fees and supporting data), or RequestHandler::FEEDETAILS (total fees along with the fee breakdown and supporting data). 
     *
     * @return FeesEstimateResult[]|bool|array Returns FeesEstimateResult[] if $output is set to RequestHandler::ALL. Returns an array of data if $output is set to RequestHandler::TOTALFEES or RequestHandler::FEEDETAILS. Returns false if there's an error.
     */

    public function submitFeeEstimateRequests(string $region = Regions::NORTH_AMERICA, string $output = self::TOTALFEES): mixed{
       try {

            $getMyFeesEstimateResponses = $this->sdk->productFees()->getMyFeesEstimates(
                $this->accessToken,
                $region,
                $this->requests
            );
        
            switch($output){
                case self::ALL:
                    $this->resetRequests();
                    return $getMyFeesEstimateResponses;
                break;

                case self::TOTALFEES:
                    $output = [];
                    foreach($getMyFeesEstimateResponses as $getMyFeesEstimateResult){
                        array_push($output,
                        [
                            "id_value" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getIdValue(),
                            "id_type" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getIdType()->toString(),
                            "is_amazon_fulfilled" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getIsAmazonFulfilled(),
                            "listing_price" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getPriceToEstimateFees()->getListingPrice()->getAmount(),
                            "listing_price_currency_code" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getPriceToEstimateFees()->getListingPrice()->getCurrencyCode(),
                            "shipping_price" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getPriceToEstimateFees()->getShipping()->getAmount(),
                            "shipping_price_currency_code" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getPriceToEstimateFees()->getShipping()->getCurrencyCode(),
                            "total_fees" => $getMyFeesEstimateResult->getFeesEstimate()->getTotalFeesEstimate()->getAmount(),
                            "total_fees_currency_code" => $getMyFeesEstimateResult->getFeesEstimate()->getTotalFeesEstimate()->getCurrencyCode()
                        ]);
                        
                    }
                    $this->resetRequests();
                    return $output;
                break;

                case self::FEEDETAILS:
                    $output = [];
                    foreach($getMyFeesEstimateResponses as $getMyFeesEstimateResult){
                        $fees_summary = [
                            "id_value" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getIdValue(),
                            "id_type" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getIdType()->toString(),
                            "is_amazon_fulfilled" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getIsAmazonFulfilled(),
                            "listing_price" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getPriceToEstimateFees()->getListingPrice()->getAmount(),
                            "listing_price_currency_code" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getPriceToEstimateFees()->getListingPrice()->getCurrencyCode(),
                            "shipping_price" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getPriceToEstimateFees()->getShipping()->getAmount(),
                            "shipping_price_currency_code" => $getMyFeesEstimateResult->getFeesEstimateIdentifier()->getPriceToEstimateFees()->getShipping()->getCurrencyCode(),
                            "total_fees" => $getMyFeesEstimateResult->getFeesEstimate()->getTotalFeesEstimate()->getAmount(),
                            "total_fees_currency_code" => $getMyFeesEstimateResult->getFeesEstimate()->getTotalFeesEstimate()->getCurrencyCode()
                        ];

                        foreach($getMyFeesEstimateResult->getFeesEstimate()->getFeeDetailList() as $feeDetailList){
                            $fees_summary[strtolower($feeDetailList->getFeeType())]['fee_amount'] = $feeDetailList->getFeeAmount()->getAmount();
                            $fees_summary[strtolower($feeDetailList->getFeeType())]['fee_currency_code'] = $feeDetailList->getFeeAmount()->getCurrencyCode();

                            if($feeDetailList->getFeePromotion() !== null){
                                $fees_summary[strtolower($feeDetailList->getFeeType())]['fee_promotion_amount'] = $feeDetailList->getFeePromotion()->getAmount();
                                $fees_summary[strtolower($feeDetailList->getFeeType())]['fee_promotion_currency_code'] = $feeDetailList->getFeePromotion()->getCurrencyCode();
                            } 
                            
                            if($feeDetailList->getTaxAmount() !== null){
                                $fees_summary[strtolower($feeDetailList->getFeeType())]['fee_tax_amount'] = $feeDetailList->getTaxAmount()->getAmount();
                                $fees_summary[strtolower($feeDetailList->getFeeType())]['fee_tax_currency_code'] = $feeDetailList->getTaxAmount()->getCurrencyCode();
                            }

                            $fees_summary[strtolower($feeDetailList->getFeeType())]['final_fee_amount'] = $feeDetailList->getFinalFee()->getAmount();
                            $fees_summary[strtolower($feeDetailList->getFeeType())]['final_fee_currency_code'] = $feeDetailList->getFinalFee()->getCurrencyCode();
                        }
                        array_push($output, $fees_summary);
                    }
                    $this->resetRequests();
                    return $output;
                break;

                default:
                    throw new \Exception("Invalid output type selector. Must be ALL, TOTALFEES, or FEEDETAILS.");
            }

        
        } catch (\Exception $exception) {
           $this->logger->error($exception->getMessage());
           return false;
        }
    }
}

