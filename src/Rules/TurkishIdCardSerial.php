<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class TurkishIdCardSerial implements Rule
{
    public function passes($attribute, $value): bool
    {
        return (bool) preg_match('/^[A-Z]{1}[0-9]{2}[A-Z]{1}[0-9]{5}$/', (string) $value);
    }

    public function message(): string
    {
        return __('turkiye-validator::validation.tr_id_card_serial');
    }
}
