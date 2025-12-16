<?php

use Illuminate\Support\Facades\Validator;

test('faker generates valid turkish id', function () {
    $faker = app(\Faker\Generator::class);
    $id = $faker->turkishIdNumber();
    
    expect(Validator::make(['id' => $id], ['id' => 'turkish_id'])->passes())->toBeTrue();
});

test('faker generates valid turkish phone', function () {
    $faker = app(\Faker\Generator::class);
    $phone = $faker->turkishPhoneNumber();
    
    expect(Validator::make(['phone' => $phone], ['phone' => 'turkish_phone'])->passes())->toBeTrue();
});

test('faker generates valid tax id', function () {
    $faker = app(\Faker\Generator::class);
    $tax = $faker->turkishTaxIdNumber();
    
    expect(Validator::make(['tax' => $tax], ['tax' => 'tax_id'])->passes())->toBeTrue();
});

test('faker generates valid license plate', function () {
    $faker = app(\Faker\Generator::class);
    $plate = $faker->turkishLicensePlate();
    
    expect(Validator::make(['plate' => $plate], ['plate' => 'license_plate'])->passes())->toBeTrue();
});

test('faker generates valid iban', function () {
    $faker = app(\Faker\Generator::class);
    $iban = $faker->turkishIban();
    
    expect(Validator::make(['iban' => $iban], ['iban' => 'turkish_iban'])->passes())->toBeTrue();
});

test('faker generates valid id card serial', function () {
    $faker = app(\Faker\Generator::class);
    $serial = $faker->turkishIdCardSerial();
    
    expect(Validator::make(['serial' => $serial], ['serial' => 'tr_id_card_serial'])->passes())->toBeTrue();
});
