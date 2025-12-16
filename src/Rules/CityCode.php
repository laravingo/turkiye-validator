<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class CityCode implements Rule
{
    public function passes($attribute, $value): bool
    {
        $code = (int) $value;

        return $code >= 1 && $code <= 81;
    }

    public function message(): string
    {
        return __('turkiye-validator::validation.city_code');
    }
}
