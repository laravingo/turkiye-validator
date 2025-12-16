<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class TaxIdentityNumber implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (strlen((string) $value) !== 10 || !ctype_digit((string) $value)) {
            return false;
        }

        $digits = array_map('intval', str_split((string) $value));
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $tmp = ($digits[$i] + 10 - ($i + 1)) % 10;
            
            if ($tmp === 9) {
                $sum += 9;
            } else {
                $sum += ($tmp * (pow(2, 10 - ($i + 1)))) % 9;
            }
        }

        $checksum = (10 - ($sum % 10)) % 10;

        return $checksum === $digits[9];
    }

    public function message(): string
    {
        return __('turkiye-validator::validation.tax_id');
    }
}
