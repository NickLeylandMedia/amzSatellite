<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/models/offerRequest.php';

use offerRequest;
use DateTime;


class Formatter
{
    public static function arrayToOfferRequest($arr) {
        $reqArr = [];
        foreach ($arr as $item) {
            $req = new offerRequest($item, 'ATVPDKIKX0DER', 'New', 'Consumer', 'Offers');
            array_push($reqArr, $req);
        }
        return $reqArr;
    }

    public static function stringToDBDate($date) {
        $rawDate = new DateTime($date);
        $final = $rawDate->format('Y-m-d H:i:s');
        return $final;
    }

    public static function dateToISO($date) {
        $rawDate = new DateTime($date);
        $final = $rawDate->format(DateTime::ATOM);
        return $final;
    }


    
}



?>