<?php

namespace Laravingo\TurkiyeValidator\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            \Laravingo\TurkiyeValidator\TurkiyeValidatorServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Turkiye' => \Laravingo\TurkiyeValidator\Facades\Turkiye::class,
        ];
    }
}
