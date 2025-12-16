<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class TurkishPhoneNumber implements Rule
{
    public function passes($attribute, $value): bool
    {
        return (bool) preg_match('/^(\+90|0)?5[0-9]{9}$/', (string) $value);
    }

    public function message(): string
    {
        return __('turkiye-validator::validation.turkish_phone');
    }
}
