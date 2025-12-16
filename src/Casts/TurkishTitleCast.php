<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Laravingo\TurkiyeValidator\Utilities\TurkishSanitizer;

class TurkishTitleCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return (new TurkishSanitizer())->toUpper((string) $value);
    }
}
