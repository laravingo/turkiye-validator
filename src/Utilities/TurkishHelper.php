<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Utilities;

class TurkishHelper
{
    public function sanitize(): TurkishSanitizer
    {
        return new TurkishSanitizer();
    }

    public function formatPhoneNumber(string $phoneNumber): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (strlen($cleaned) === 10 && str_starts_with($cleaned, '5')) {
             return '0' . $cleaned;
        }
        
        if (strlen($cleaned) === 11 && str_starts_with($cleaned, '05')) {
            return $cleaned;
        }

        if (strlen($cleaned) === 12 && str_starts_with($cleaned, '905')) {
            return '0' . substr($cleaned, 2);
        }

        return $phoneNumber;
    }
}
