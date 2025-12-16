<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Rules;

use Illuminate\Contracts\Validation\Rule;

class TurkishIban implements Rule
{
    public function passes($attribute, $value): bool
    {
        $iban = strtoupper(str_replace(' ', '', (string) $value));

        if (strlen($iban) !== 26 || !str_starts_with($iban, 'TR')) {
            return false;
        }

        $rearranged = substr($iban, 4) . substr($iban, 0, 4);

        $numericIban = '';
        foreach (str_split($rearranged) as $char) {
            $numericIban .= is_numeric($char) ? $char : (ord($char) - 55);
        }

        return bcmod($numericIban, '97') === '1';
    }

    public function message(): string
    {
        return __('turkiye-validator::validation.turkish_iban');
    }
}
