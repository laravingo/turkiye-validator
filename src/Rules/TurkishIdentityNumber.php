<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class TurkishIdentityNumber implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!preg_match('/^[1-9]{1}[0-9]{10}$/', (string) $value)) {
            return false;
        }

        $digits = array_map('intval', str_split((string) $value));

        $sumOdds = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
        $sumEvens = $digits[1] + $digits[3] + $digits[5] + $digits[7];

        $checksum1 = (($sumOdds * 7) - $sumEvens) % 10;
        
        if ($checksum1 !== $digits[9]) {
            return false;
        }

        $checksum2 = ($sumOdds + $sumEvens + $digits[9]) % 10;

        return $checksum2 === $digits[10];
    }

    public function message(): string
    {
        return __('turkiye-validator::validation.turkish_id');
    }
}
