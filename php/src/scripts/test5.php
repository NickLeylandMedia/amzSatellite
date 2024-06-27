<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

// require_once '../Token.php';

use Dotenv\Dotenv;
use amzSatellite\eBay;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../.env")->load();

$client_id = 'BlackHal-Shipstat-PRD-1e6e3d7c5-85a1ff0b';
$redirect_uri = 'Black_Hall_Outf-BlackHal-Shipst-eoektlg';
$scope = 'https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.fulfillment';


//List of url encoded scopes
$scopes = rawurlencode($scope);

var_dump($scopes);


$authorization_url = "https://auth.ebay.com/oauth2/authorize?client_id=BlackHal-Shipstat-PRD-1e6e3d7c5-85a1ff0b&redirect_uri=Black_Hall_Outf-BlackHal-Shipst-eoektlg&response_type=code&scope=https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.fulfillment";

header("Location: $authorization_url");
exit();
