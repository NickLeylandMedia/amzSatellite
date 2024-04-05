<?php
declare(strict_types=1);

use SellingPartnerApi\SellingPartnerApi;
use SellingPartnerApi\Enums\Endpoint;

require_once __DIR__ . '\vendor\autoload.php';

use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();



$connector = SellingPartnerApi::make(
    clientId: $clientId,
    clientSecret: $clientSecret,
    refreshToken: $refreshToken,
    endpoint: Endpoint::NA,  // Or Endpoint::EU, Endpoint::FE, Endpoint::NA_SANDBOX, etc.
)->seller();




