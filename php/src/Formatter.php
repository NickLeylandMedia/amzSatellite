<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/models/offerRequest.php';

use offerRequest;


class FormatReqs
{
    public function arrayToOfferRequest($arr) {
        $reqArr = [];
        foreach ($arr as $item) {
            $req = new offerRequest($item, 'ATVPDKIKX0DER', 'New', 'Consumer', 'Offers');
            array_push($reqArr, $req);
        }
        return $reqArr;
    }

    
}



?>