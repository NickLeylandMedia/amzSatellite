<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../.env")->load();

use amzSatellite\UtilDBConnection;
use amzSatellite\APIConnection;
use amzSatellite\Processor;

// $utilDB = new UtilDBConnection();

$api = new APIConnection();

// $offer = $api->getOffersByASIN("B0CYXYM55N");

$proc = new Processor();

$req = $api->bulkGetOffersByASIN(["B0CYXYM55N", "B0CYXZVRVC", "B08JHBYWT6", "B085JVR1ML", "B0CLTZR9DJ", "B0CLV1MQRM", "B0CP69P72B", "B085J1WQWC", "B08JHC1KFH", "B0CLTYX9DN", "B0CP6B2SFW", "B0CP6B9SMP", "B0CLV12DWT", "B085JKC9JM", "B085JKB187", "B085K32J4R", "B085JK7D4M", "B0CLV1Y2RX", "B0CP6B7WF5", "B085J1F1WF", "B0CP6BY19L", "B0CLTZ35RL", "B085JQXWCM", "B085J2CP53", "B0CLTZ5HCG", "B0CPGB4CQG", "B0CPFYCQYP", "B085JJZQ31", "B085J1NB3X"], true);



$final = $proc->separateByAsin($req, true);

var_dump($final);
