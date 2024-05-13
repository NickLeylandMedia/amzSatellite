<?php

declare(strict_types=1);

namespace amzSatellite;

// Enabling Composer Packages
require __DIR__ . '/../vendor/autoload.php';

class Mailer
{
    public static function sendMail($to, $subject, $message, $headers)
    {
        mail($to, $subject, $message, $headers);
    }
}
