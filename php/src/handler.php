<?php

declare(strict_types=1);

namespace requestHandler;

use Exception;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

class requestHandler 
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

    public function showQueue() {
        var_dump($this->requests);
    }

    public function clearQueue() {
        $this->requests = [];
    }


    public function processQueue() {
        foreach ($this->requests as $request) {
            // echo $request->reqType;
            switch($request->reqType) {
                case "Offers":
                    echo "Offers";
                    break;
                case "Fees":
                    echo "Fees";
                    break;
                default:
                    throw new Exception("Invalid Request Type");

            }        
        }
        // if ($this->getRequestCount() <= 20) {
        //     foreach ($this->requests as $request) {
                
        //     }
        // } 
    }


}



?>