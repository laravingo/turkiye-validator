<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Facades;

use Illuminate\Support\Facades\Facade;

class Turkiye extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'turkiye';
    }
}
