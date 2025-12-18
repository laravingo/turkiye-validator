# Turkiye Validator

<p align="center">
    <img src=".github/assets/cover-image.png" alt="Laravingo Turkiye Validator Cover Image" width="100%" style="border-radius: 8px;">
</p>

<p align="center">
    <a href="README.md">🇺🇸 English</a> | 
    <a href="README.tr.md">🇹🇷 Türkçe</a> | 
    <a href="README.es.md">🇪🇸 Español</a>
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravingo/turkiye-validator.svg?style=flat-square)](https://packagist.org/packages/laravingo/turkiye-validator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/laravingo/turkiye-validator/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/laravingo/turkiye-validator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/laravingo/turkiye-validator.svg?style=flat-square)](https://packagist.org/packages/laravingo/turkiye-validator)
[![License](https://img.shields.io/packagist/l/laravingo/turkiye-validator.svg?style=flat-square)](https://packagist.org/packages/laravingo/turkiye-validator)

![PHP Version](https://img.shields.io/badge/php-%5E8.2-777BB4.svg?style=flat-square)
![Laravel Version](https://img.shields.io/badge/laravel-10.x%20%7C%2011.x%20%7C%2012.x-FF2D20.svg?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

**Laravel'de Türk verilerinin doğrulanması, temizlenmesi ve test edilmesi için en kapsamlı araç seti.**

Bu paket, Türk veri gereksinimleri (T.C. Kimlik No, VKN, Plakalar, IBAN, KEP vb.) için özel olarak hazırlanmış kapsamlı doğrulama kuralları (Resmi Algoritmalar), temizleme araçları, Eloquent "cast"leri ve Faker sağlayıcıları sunar.

---

## Kurulum

Paketi composer aracılığıyla yükleyebilirsiniz:

```bash
composer require laravingo/turkiye-validator
```

### Varlıkların Yayınlanması (Publishing Assets)

Konfigürasyonu veya hata mesajlarını özelleştirmek için varlıkları yayınlayın:

```bash
php artisan vendor:publish --tag=turkiye-validator-config
```

---

## Yapılandırma

Yayınladıktan sonra, paketi `config/turkiye-validator.php` dosyasında yapılandırabilirsiniz:

```php
return [
    // Telefon formatı seçenekleri: 'E164' (+905...), 'NATIONAL' (05...), 'RAW' (5...)
    'phone_format' => 'E164', 

    // Kimlik numaralarını maskelemek için kullanılan karakter
    'mask_char' => '*', 
];
```

---

## Doğrulama Kuralları

Bu paket, basit regex eşleştirmesi yerine resmi matematiksel algoritmaları (sağlama toplamları, modülo kontrolleri) kesinlikle uygular.

### Mevcut Kurallar

| Kural Adı | Açıklama | Örnek Girdi |
|---|---|---|
| `turkish_id` | T.C. Kimlik Numarasını doğrular (11 hane, Algo Kontrolü). | `10000000146` |
| `turkish_phone` | Türk Cep Telefonu Numaralarını doğrular. | `555 123 45 67` veya `0555...` |
| `tax_id` | Vergi Kimlik Numarasını doğrular (VKN, 10 hane, Mod-10). | `1234567890` |
| `license_plate` | Türk Plakalarını doğrular (Şehir Kodu 01-81). | `34 ABC 123` |
| `turkish_iban` | Türk IBAN'larını doğrular (TR öneki + Mod-97 sağlama toplamı). | `TR12000...` |
| `tr_id_card_serial`| Yeni Kimlik Kartı Seri Numaralarını doğrular. | `A12F34567` |
| `kep_address` | Kayıtlı Elektronik Posta (KEP) adreslerini doğrular. | `info@company.kep.tr` |
| `city_code` | Türk Şehir Plaka Kodlarını doğrular (1-81). | `34`, `6`, `81` |

### Kullanım Örneği

Controller veya Form Request sınıfınızda:

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

## Adres & Veri Servisi

Paket, Türk şehirlerinin ve ilçelerinin resmi listelerine kolayca erişmek için bir veri servisi içerir.

### Kullanım

```php
use Laravingo\TurkiyeValidator\Facades\Turkiye;

// Tüm Şehirleri Getir (Plaka Kodu => İsim)
$cities = Turkiye::cities(); 
// Dönen Değer: [1 => 'Adana', ..., 34 => 'İstanbul', ...]

// Bir Şehrin İlçelerini Getir (Plaka Koduna göre)
$districts = Turkiye::districts(34); 
// Dönen Değer: ['Adalar', 'Arnavutköy', 'Ataşehir', ...]
```

---

## Yardımcı Fonksiyonlar

Hassas verileri biçimlendirmek ve maskelemek için `Turkiye` facade'i üzerinden yardımcı araçlar mevcuttur.

### Telefon Biçimlendirme

```php
// Girdi karışık olabilir: "0532 123 45 67" veya "532-123-4567"
$formatted = Turkiye::formatPhoneNumber('0532 123 45 67');

// Çıktı 'phone_format' ayarına bağlıdır:
// 'E164':     "+905321234567" (Varsayılan)
// 'NATIONAL': "05321234567"
// 'RAW':      "5321234567"
```

### Kimlik Maskeleme

```php
$masked = Turkiye::maskIdentityNumber('12345678901');

// Çıktı ('mask_char' ayarına göre): "123******01"
```

---

## Temizleme & Yardımcılar

Paket, karışık girdileri temizlemek için bir `TurkishSanitizer` sınıfı (ve bir `Turkiye` facade'i) sağlar. Sunucu yerel ayarından (locale) bağımsız olarak Türkçe karakter dönüşümünü (i/İ/I/ı) doğru bir şekilde gerçekleştirir.

### Kullanım

```php
use Laravingo\TurkiyeValidator\Utilities\TurkishSanitizer;

$sanitizer = new TurkishSanitizer();

// Başlık Düzeni (i/İ/I/ı karakterlerini doğru işler)
echo $sanitizer->toTitle('i̇stanbul ve IĞDIR'); 
// Çıktı: "İstanbul Ve Iğdır"

// Telefon Numarasını Temizle (Sadece 10 haneyi döndürür)
echo $sanitizer->cleanPhone('0 (555) 123-45 67');
// Çıktı: "5551234567"

// IBAN Temizle (Büyük Harf + Boşluksuz)
echo $sanitizer->cleanIban('tr 12 34 56...');
// Çıktı: "TR123456..."
```

---

## Eloquent Casts (Pro Özellik)

Verilerinizi veritabanına kaydedilmeden *önce* Laravel Custom Casts (Özel Dönüştürücüler) kullanarak otomatik olarak temizleyin ve biçimlendirin.

### Model İçinde Kullanım

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravingo\TurkiyeValidator\Casts\TurkishPhoneCast;
use Laravingo\TurkiyeValidator\Casts\TurkishIbanCast;
use Laravingo\TurkiyeValidator\Casts\TurkishTitleCast;

class User extends Model
{
    protected $casts = [
        'phone'      => TurkishPhoneCast::class, // 10 haneye otomatik temizler
        'iban'       => TurkishIbanCast::class,  // Büyük Harf + Boşluksuz
        'full_name'  => TurkishTitleCast::class, // Başlık Düzenine otomatik çevirir (Türkçe mantığı)
    ];
}
```

```php
// Artık $user->phone = '(555) 123'; $user->save(); yaptığınızda, DB'ye 555123... olarak kaydedilir.
```

---

## Faker Sağlayıcısı (Test)

Fabrikalarınızda (factories) ve seed dosyalarınızda **matematiksel olarak geçerli** test verileri oluşturabilmeniz için bir Faker sağlayıcısını otomatik olarak kaydediyoruz.

### Kullanım

```php
// Bir Factory veya Seeder içinde
$validId    = fake()->turkishIdNumber();    // Geçerli sağlama toplamı
$validIban  = fake()->turkishIban();        // Geçerli TR IBAN
$validTax   = fake()->turkishTaxIdNumber(); // Geçerli VKN
$validPlate = fake()->turkishLicensePlate();// Geçerli 06 ABC 123
$validPhone = fake()->turkishPhoneNumber(); // Geçerli +905...
```

---

## Yerelleştirme

Paket, İngilizce (`en`) ve Türkçe (`tr`) dillerini kutudan çıktığı gibi destekler.

Dili değiştirmek için, `config/app.php` dosyasındaki Laravel uygulama yerel ayarını değiştirmeniz yeterlidir:

```php
'locale' => 'tr',
```

Hata mesajları otomatik olarak Türkçe'ye geçecektir (örneğin, "Geçerli bir T.C. Kimlik Numarası olmalıdır").

---

## Lisans

MIT Lisansı (MIT). Daha fazla bilgi için lütfen [Lisans Dosyasına](https://github.com/laravingo/turkiye-validator/blob/main/LICENSE.md) bakın.

---

## Test

Bu paket otomatik testler için [Pest PHP](https://pestphp.com) kullanır.

```bash
composer test
```
        
Belirli testleri çalıştırmak için:
        
```bash
vendor/bin/pest --filter=ValidationRulesTest
```
