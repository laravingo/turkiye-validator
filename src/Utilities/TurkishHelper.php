<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Utilities;

class TurkishHelper
{
    public function formatPhoneNumber(string $phoneNumber): ?string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

        // If it starts with 90 and is 12 digits, remove 90
        if (str_starts_with($cleaned, '90') && strlen($cleaned) === 12) {
            $cleaned = substr($cleaned, 2);
        }

        // If it starts with 0 and is 11 digits, remove 0
        if (str_starts_with($cleaned, '0') && strlen($cleaned) === 11) {
            $cleaned = substr($cleaned, 1);
        }

        // Now we expect 10 digits
        if (strlen($cleaned) !== 10) {
            return null;
        }

        $format = config('turkiye-validator.phone_format', 'E164');

        if ($format === 'RAW') {
            return $cleaned;
        }

        if ($format === 'NATIONAL') {
            return '0'.$cleaned;
        }

        // Default E164
        return '+90'.$cleaned;
    }

    public function maskIdentityNumber(string $identityNumber): string
    {
        if (strlen($identityNumber) !== 11) {
            return $identityNumber;
        }

        $maskChar = config('turkiye-validator.mask_char', '*');
        $visibleStart = substr($identityNumber, 0, 3);
        $visibleEnd = substr($identityNumber, -2);
        $maskedPart = str_repeat($maskChar, 6);

        return $visibleStart.$maskedPart.$visibleEnd;
    }

    public function sanitize(string $value, string $method = 'toUpper'): string
    {
        $sanitizer = new TurkishSanitizer;

        if (method_exists($sanitizer, $method)) {
            return $sanitizer->$method($value);
        }

        return $value;
    }

    public function cities(): array
    {
        return app('turkiye.address')->getCities();
    }

    public function districts(int $plateCode): array
    {
        return app('turkiye.address')->getDistricts($plateCode);
    }
}
