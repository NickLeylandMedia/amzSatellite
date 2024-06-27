<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use amzSatellite\eBay;
use amzSatellite\UtilDBConnection;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../.env")->load();

//Initialize DB UTIL Connection
$util = new UtilDBConnection();


//Get Auth Code From Post Request
$authCode = $_GET['code'];
//Declare Variables For Request
$client_id = 'BlackHal-Shipstat-PRD-1e6e3d7c5-85a1ff0b';
$client_secret = 'PRD-e6e3d7c501b5-bb65-4651-bbc8-0aec';
$authorization_code = $authCode;
$redirect_uri = 'Black_Hall_Outf-BlackHal-Shipst-eoektlg';
$token_url = 'https://api.ebay.com/identity/v1/oauth2/token';
$basic_auth = base64_encode("$client_id:$client_secret");

//Configure Headers
$headers = [
    "Content-Type: application/x-www-form-urlencoded",
    "Authorization: Basic $basic_auth"
];
//Configure POST data
$data = [
    'grant_type' => 'authorization_code',
    'code' => $authorization_code,
    'redirect_uri' => $redirect_uri
];
//Configure request options
$options = [
    CURLOPT_URL => $token_url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($data),
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_RETURNTRANSFER => true
];
//Execute curl request
$ch = curl_init();
curl_setopt_array($ch, $options);
$response = curl_exec($ch);
//Decode the response
$final = json_decode($response, true);

var_dump($final);


//Process the data retrived from OAuth
if ($response === false) {
    $error = curl_error($ch);
    echo "Error: $error";
} else {
    $accessToken = $final['access_token'];
    $refreshToken = $final['refresh_token'];
    $expiration = $final['expires_in'];
    //Make a new datetime stamp and add the expiration time to get the expiration datetime
    $expiration = date('Y-m-d H:i:s', strtotime("+$expiration seconds"));

    try {
        $util->addEbaySession($accessToken, $refreshToken, $expiration);
    } catch (Exception $th) {
        var_dump($th->getMessage());
    }
}

curl_close($ch);
