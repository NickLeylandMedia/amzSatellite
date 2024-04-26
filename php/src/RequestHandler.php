<?php

declare(strict_types=1);

namespace amzSatellite;


// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

use Exception;

use amzSatellite\APIConnection;
use amzSatellite\ShippingRates;

class RequestHandler 
{
    private array $requests = [];
    private array $retries = [];
    private array $responses = [];

    public function getRequestCount() {
        return count($this->requests);
    }



    public function addRequest($payload) {
        array_push($this->requests, $payload);
    }

    public function bulkAddRequest($arr) {
        foreach ($arr as $item) {
            array_push($this->requests, $item);
        }
    }

    public function showQueue() {
        var_dump($this->requests);
    }

    public function clearQueue() {
        $this->requests = [];
    }


    //PHP function to process an array with exponential backoff if request fails and push failures to another array
    public function processQueue() {
        $api = new APIConnection();
        $retryArr = [];
        $resultsArr = [];
        foreach ($this->requests as $request) {
            switch($request->reqType) {
                case "Offers":
                    try {
                        $response = $api->getOffersByASIN($request->asin, $request->marketplace, $request->condition, $request->customerType);
                        array_push($resultsArr, $response);
                    } catch (Exception $e) {
                       //If error, do an exponential backoff until success
                       echo "Wait Triggered";
                        $retries = 0;
                        $wait = 1;
                        if ($retries == 10) {
                            array_push($retryArr, $request);
                            $retries = 0;
                            break;
                        }
                        while ($retries < 10) {
                            $retries++;
                            sleep($wait);
                            $wait = $wait * 2;
                            try {
                                //Make request
                            } catch (Exception $e) {
                                $wait++;
                                break;
                            }
                            break;
                        }
                    }
                    file_put_contents('audit.json', json_encode($resultsArr));
                    break;
                case "Fees":
                    echo "Fees";
                    break;
                case "Rates":
                    try {
                        $response = ShippingRates::getShippingRates($request->carrier, $request->service, $request->fromPostalCode, $request->toPostalCode, $request->weight, $request->length, $request->width, $request->height);
                        array_push($resultsArr, $response);
                    } catch(Exception $e) {
                        //If error, do an exponential backoff until success
                        echo "Wait Triggered";
                        $retries = 0;
                        $wait = 1;
                        if ($retries == 10) {
                            array_push($retryArr, $request);
                            $retries = 0;
                            break;
                        }
                        while ($retries < 10) {
                            $retries++;
                            sleep($wait);
                            $wait = $wait * 2;
                            try {
                                //Make request
                            } catch (Exception $e) {
                                $wait++;
                                break;
                            }
                            break;
                        }
                    }
                    break;
                default:
                    throw new Exception("Invalid Request Type");

            }        
        }  
        return $resultsArr;  
    } 
}



?>