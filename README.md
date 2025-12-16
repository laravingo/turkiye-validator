# Turkiye Validator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravingo/turkiye-validator.svg?style=flat-square)](https://packagist.org/packages/laravingo/turkiye-validator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/laravingo/turkiye-validator/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/laravingo/turkiye-validator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/laravingo/turkiye-validator.svg?style=flat-square)](https://packagist.org/packages/laravingo/turkiye-validator)
[![License](https://img.shields.io/packagist/l/laravingo/turkiye-validator.svg?style=flat-square)](https://packagist.org/packages/laravingo/turkiye-validator)

![PHP Version](https://img.shields.io/badge/php-%5E8.2-777BB4.svg?style=flat-square)
![Laravel Version](https://img.shields.io/badge/laravel-10.x%20%7C%2011.x-FF2D20.svg?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

**The ultimate Swiss Army Knife for Turkish data validation, sanitization, and testing in Laravel.**

This package provides a comprehensive suite of validation rules (Official Algorithms), sanitization utilities, Eloquent casts, and Faker providers specifically tailored for Turkish data requirements (Identity Numbers, VKN, License Plates, IBAN, KEP, etc.).

---

## Installation

You can install the package via composer:

```bash
composer require laravingo/turkiye-validator
```

### Publishing Assets

To customize the configuration or error messages, publish the assets:

```bash
php artisan vendor:publish --tag=turkiye-validator-config
```

---

## ⚙️ Configuration

After publishing, you can configure the package in `config/turkiye-validator.php`:

```php
return [
    // Phone format options: 'E164' (+905...), 'NATIONAL' (05...), 'RAW' (5...)
    'phone_format' => 'E164', 

    // Character used for masking identity numbers
    'mask_char' => '*', 
];
```

---

## 🔥 Validation Rules

This package strictly implements official mathematical algorithms (checksums, modulo checks) rather than simple regex matching.

### Available Rules

| Rule Name | Description | Example Input |
|---|---|---|
| `turkish_id` | Validates T.C. Identity Number (11 digits, Algo Check). | `10000000146` |
| `turkish_phone` | Validates Turkish Mobile Numbers. | `555 123 45 67` or `0555...` |
| `tax_id` | Validates Tax ID (Vergi Kimlik No, 10 digits, Mod-10). | `1234567890` |
| `license_plate` | Validates Turkish License Plates (City Code 01-81). | `34 ABC 123` |
| `turkish_iban` | Validates Turkish IBANs (TR prefix + Mod-97 checksum). | `TR12000...` |
| `tr_id_card_serial`| Validates New Identity Card Serial Numbers. | `A12F34567` |
| `kep_address` | Validates Registered Electronic Mail (KEP) addresses. | `info@company.kep.tr` |
| `city_code` | Validates Turkish City Plate Codes (1-81). | `34`, `6`, `81` |

### Usage Example

In your Controller or Form Request:

```php
$request->validate([
    'identity_number' => 'required|turkish_id',
    'phone'           => 'required|turkish_phone',
    'tax_number'      => 'nullable|tax_id',
    'plate_code'      => 'required|license_plate',
    'iban'            => 'required|turkish_iban',
    'serial_no'       => 'required|tr_id_card_serial',
    'kep_email'       => 'required|kep_address',
    'city'            => 'required|city_code',
]);
```

---

## 🏙️ Address & Data Service

The package includes a data service to easily access official lists of Turkish cities and districts.

### Usage

```php
use Laravingo\TurkiyeValidator\Facades\Turkiye;

// Get All Cities (Plate Code => Name)
$cities = Turkiye::cities(); 
// Returns: [1 => 'Adana', ..., 34 => 'İstanbul', ...]

// Get Districts for a City (by Plate Code)
$districts = Turkiye::districts(34); 
// Returns: ['Adalar', 'Arnavutköy', 'Ataşehir', ...]
```

---

## 🛠️ Helper Functions

Utility helpers are available via the `Turkiye` facade to format and mask sensitive data.

### Phone Formatting

```php
// Input can be messy: "0532 123 45 67" or "532-123-4567"
$formatted = Turkiye::formatPhoneNumber('0532 123 45 67');

// Output depends on 'phone_format' config:
// 'E164':     "+905321234567" (Default)
// 'NATIONAL': "05321234567"
// 'RAW':      "5321234567"
```

### Identity Masking

```php
$masked = Turkiye::maskIdentityNumber('12345678901');

// Output (based on 'mask_char'): "123******01"
```

---

## 🧼 Sanitization & Helper

The package provides a `TurkishSanitizer` class (and a `Turkiye` facade) to clean messy input. It explicitly handles Turkish character conversion (i/İ/I/ı) correctly, regardless of server locale.

### Usage

```php
use Laravingo\TurkiyeValidator\Utilities\TurkishSanitizer;

$sanitizer = new TurkishSanitizer();

// Title Case (Correctly handles i/İ/I/ı)
echo $sanitizer->toTitle('i̇stanbul ve IĞDIR'); 
// Output: "İstanbul Ve Iğdır"

// Clean Phone Number (Returns pure 10 digits)
echo $sanitizer->cleanPhone('0 (555) 123-45 67');
// Output: "5551234567"

// Clean IBAN (Uppercase + No Spaces)
echo $sanitizer->cleanIban('tr 12 34 56...');
// Output: "TR123456..."
```

---

## 💎 Eloquent Casts (Pro Feature)

Automatically clean and format your data *before* it is saved to the database using Laravel Custom Casts.

### Usage in Model

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravingo\TurkiyeValidator\Casts\TurkishPhoneCast;
use Laravingo\TurkiyeValidator\Casts\TurkishIbanCast;
use Laravingo\TurkiyeValidator\Casts\TurkishTitleCast;

class User extends Model
{
    protected $casts = [
        'phone'      => TurkishPhoneCast::class, // Auto-cleans to 10 digits
        'iban'       => TurkishIbanCast::class,  // Uppercase + No Spaces
        'full_name'  => TurkishTitleCast::class, // Auto-converts to Title Case (Turkish logic)
    ];
}
```

Now, when you do `$user->phone = '(555) 123'; $user->save();`, it saves `555123...` to the DB.

---

## 🎭 Faker Provider (Testing)

We automatically register a Faker provider so you can generate **mathematically valid** test data in your factories and seeds.

### Usage

```php
// In a Factory or Seeder
$validId    = fake()->turkishIdNumber();    // Valid checksum
$validIban  = fake()->turkishIban();        // Valid TR IBAN
$validTax   = fake()->turkishTaxIdNumber(); // Valid VKN
$validPlate = fake()->turkishLicensePlate();// Valid 06 ABC 123
$validPhone = fake()->turkishPhoneNumber(); // Valid +905...
```

---

## 🌍 Localization

The package supports English (`en`) and Turkish (`tr`) out of the box.

To change the language, simply set your Laravel app locale in `config/app.php`:

```php
'locale' => 'tr',
```

The error messages will automatically switch to Turkish (e.g., "Geçerli bir T.C. Kimlik Numarası olmalıdır").

---

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

---

## 🧪 Testing

This package uses [Pest PHP](https://pestphp.com) for automated testing.

```bash
composer test
```
        
To run specific tests:
        
```bash
vendor/bin/pest --filter=ValidationRulesTest
```
