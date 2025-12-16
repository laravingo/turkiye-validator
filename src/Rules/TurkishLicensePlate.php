<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class TurkishLicensePlate implements Rule
{
    public function passes($attribute, $value): bool
    {
        $value = strtoupper(str_replace(' ', '', (string) $value));

        if (!preg_match('/^(\d{2})([A-Z]{1,3})(\d{2,4})$/', $value, $matches)) {
            return false;
        }

        $cityCode = (int) $matches[1];
        
        if ($cityCode < 1 || $cityCode > 81) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return __('turkiye-validator::validation.license_plate');
    }
}
