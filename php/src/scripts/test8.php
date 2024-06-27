<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use amzSatellite\Audit;
use amzSatellite\ShippingRates;
use amzSatellite\Manifest;
use amzSatellite\Parser;
use amzSatellite\Formatter;
use amzSatellite\Loader;
use amzSatellite\APIConnection;
use amzSatellite\ShopDBConnection;
use amzSatellite\Sellers;
use amzSatellite\RequestHandler;
use amzSatellite\ShipstationUpdater;
use amzSatellite\Mailer;
use amzSatellite\Loader2;
use amzSatellite\UtilDBConnection;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, "/../../.env")->load();
