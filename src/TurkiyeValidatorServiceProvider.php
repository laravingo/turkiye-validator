<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Faker\Generator as FakerGenerator;
use Laravingo\TurkiyeValidator\Faker\TurkiyeProvider;
use Laravingo\TurkiyeValidator\Rules\TurkishIdentityNumber;
use Laravingo\TurkiyeValidator\Rules\TurkishPhoneNumber;
use Laravingo\TurkiyeValidator\Rules\TaxIdentityNumber;
use Laravingo\TurkiyeValidator\Rules\TurkishLicensePlate;
use Laravingo\TurkiyeValidator\Rules\TurkishIban;
use Laravingo\TurkiyeValidator\Rules\TurkishIdCardSerial;
use Laravingo\TurkiyeValidator\Rules\KepAddress;
use Laravingo\TurkiyeValidator\Rules\CityCode;
use Laravingo\TurkiyeValidator\Services\AddressService;
use Laravingo\TurkiyeValidator\Utilities\TurkishHelper;

class TurkiyeValidatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/turkiye-validator.php', 'turkiye-validator'
        );

        $this->app->singleton('turkiye', function () {
            return new TurkishHelper();
        });

        $this->app->extend(FakerGenerator::class, function ($faker) {
            $faker->addProvider(new TurkiyeProvider($faker));
            return $faker;
        });

        $this->app->singleton('turkiye.address', function () {
            return new AddressService();
        });
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'turkiye-validator');

        $this->publishes([
            __DIR__.'/../config/turkiye-validator.php' => config_path('turkiye-validator.php'),
        ], 'turkiye-validator-config');

        Validator::extend('turkish_id', function ($attribute, $value, $parameters, $validator) {
            return (new TurkishIdentityNumber())->passes($attribute, $value);
        }, __('turkiye-validator::validation.turkish_id'));

        Validator::extend('turkish_phone', function ($attribute, $value, $parameters, $validator) {
            return (new TurkishPhoneNumber())->passes($attribute, $value);
        }, __('turkiye-validator::validation.turkish_phone'));

        Validator::extend('tax_id', function ($attribute, $value, $parameters, $validator) {
            return (new TaxIdentityNumber())->passes($attribute, $value);
        }, __('turkiye-validator::validation.tax_id'));

        Validator::extend('license_plate', function ($attribute, $value, $parameters, $validator) {
            return (new TurkishLicensePlate())->passes($attribute, $value);
        }, __('turkiye-validator::validation.license_plate'));

        Validator::extend('turkish_iban', function ($attribute, $value, $parameters, $validator) {
            return (new TurkishIban())->passes($attribute, $value);
        }, __('turkiye-validator::validation.turkish_iban'));

        Validator::extend('tr_id_card_serial', function ($attribute, $value, $parameters, $validator) {
            return (new TurkishIdCardSerial())->passes($attribute, $value);
        }, __('turkiye-validator::validation.tr_id_card_serial'));

        Validator::extend('kep_address', function ($attribute, $value, $parameters, $validator) {
            return (new KepAddress())->passes($attribute, $value);
        }, __('turkiye-validator::validation.kep_address'));

        Validator::extend('city_code', function ($attribute, $value, $parameters, $validator) {
            return (new CityCode())->passes($attribute, $value);
        }, __('turkiye-validator::validation.city_code'));
    }
}
