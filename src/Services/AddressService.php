<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Services;

use Laravingo\TurkiyeValidator\Utilities\TurkishSanitizer;

class AddressService
{
    protected array $cities;

    protected array $districts;

    public function __construct()
    {
        $this->cities = require __DIR__.'/../Data/cities.php';
        $this->districts = require __DIR__.'/../Data/districts.php';
    }

    public function getCities(): array
    {
        return $this->cities;
    }

    public function getDistricts(int $plateCode): array
    {
        $districts = $this->districts[$plateCode] ?? [];
        $sanitizer = new TurkishSanitizer;

        return array_map(fn ($district) => $sanitizer->toTitle($district), $districts);
    }
}
