<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class KepAddress implements Rule
{
    public function passes($attribute, $value): bool
    {
        $value = (string) $value;
        return filter_var($value, FILTER_VALIDATE_EMAIL) && str_ends_with($value, '.kep.tr');
    }

    public function message(): string
    {
        return __('turkiye-validator::validation.kep_address');
    }
}
