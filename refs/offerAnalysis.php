<?php

declare(strict_types=1);

require_once __DIR__ . '\vendor\autoload.php';

use Blackhalloutfitters\RequestHandler;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$request_handler = new RequestHandler($_ENV["REFRESH_TOKEN"], $_ENV["CLIENT_ID"], $_ENV["CLIENT_ID_SECRET"]);

$request_handler->getOffersByASIN("B07Y5VQMT6");

