<?php

declare(strict_types=1);

namespace amzSatellite;





// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/models/asinOfferPayload.php';

use League\Csv\Reader;
use League\Csv\Writer;

use asinOfferPayload;

class Processor
{
    public static function separateByAsin($payload, $save)
    {
        $asins = [];
        foreach ($payload as $offer) {
            if (in_array($offer->asin, $asins)) {
                continue;
            } else {
                array_push($asins, $offer->asin);
            }
        }
        //Iterate through asins
        $asinsPayload = [];
        foreach ($asins as $asin) {
            $offers = [];
            foreach ($payload as $offer) {
                if ($offer->asin == $asin) {
                    array_push($offers, $offer);
                }
            }
            array_push($asinsPayload, new asinOfferPayload($asin, $offers));
        }
        if ($save) file_put_contents("finalOffers.json", json_encode($asinsPayload));
        return $asinsPayload;
    }
}
