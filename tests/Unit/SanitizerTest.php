<?php

use Laravingo\TurkiyeValidator\Utilities\TurkishSanitizer;

test('toTitle converts turkish characters correctly', function () {
    $sanitizer = new TurkishSanitizer();
    expect($sanitizer->toTitle('istanbul'))->toBe('İstanbul');
    expect($sanitizer->toTitle('IĞDIR'))->toBe('Iğdır');
    expect($sanitizer->toTitle('diyarbakır'))->toBe('Diyarbakır');
});

test('toUpper converts turkish characters correctly', function () {
    $sanitizer = new TurkishSanitizer();
    expect($sanitizer->toUpper('izmir'))->toBe('İZMİR');
    expect($sanitizer->toUpper('istanbul'))->toBe('İSTANBUL'); // simple i
});

test('cleanPhone formats number correctly', function () {
    $sanitizer = new TurkishSanitizer();
    expect($sanitizer->cleanPhone('0 (532) 123 45 67'))->toBe('5321234567');
    expect($sanitizer->cleanPhone('+905321234567'))->toBe('5321234567');
});

test('cleanIban removes spaces and uppercases', function () {
    $sanitizer = new TurkishSanitizer();
    expect($sanitizer->cleanIban('tr 12 34'))->toBe('TR1234');
});
