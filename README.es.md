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

**La navaja suiza definitiva para la validación, sanitización y pruebas de datos turcos en Laravel.**

Este paquete proporciona un conjunto completo de reglas de validación (Algoritmos Oficiales), utilidades de sanitización, casts de Eloquent y proveedores de Faker diseñados específicamente para los requisitos de datos turcos (Números de Identidad, VKN, Matrículas, IBAN, KEP, etc.).

---

## Instalación

Puedes instalar el paquete a través de composer:

```bash
composer require laravingo/turkiye-validator
```

### Publicación de Activos (Publishing Assets)

Para personalizar la configuración o los mensajes de error, publica los activos:

```bash
php artisan vendor:publish --tag=turkiye-validator-config
```

---

## Configuración

Después de publicar, puedes configurar el paquete en `config/turkiye-validator.php`:

```php
return [
    // Opciones de formato de teléfono: 'E164' (+905...), 'NATIONAL' (05...), 'RAW' (5...)
    'phone_format' => 'E164', 

    // Carácter utilizado para enmascarar números de identidad
    'mask_char' => '*', 
];
```

---

## Reglas de Validación

Este paquete implementa estrictamente algoritmos matemáticos oficiales (sumas de comprobación, verificaciones de módulo) en lugar de una simple coincidencia de expresiones regulares (regex).

### Reglas Disponibles

| Nombre de la Regla | Descripción | Entrada de Ejemplo |
|---|---|---|
| `turkish_id` | Valida el Número de Identidad T.C. (11 dígitos, Verificación de Algoritmo). | `10000000146` |
| `turkish_phone` | Valida Números de Móvil Turcos. | `555 123 45 67` o `0555...` |
| `tax_id` | Valida el ID Fiscal (Vergi Kimlik No, 10 dígitos, Mod-10). | `1234567890` |
| `license_plate` | Valida Matrículas Turcas (Código de Ciudad 01-81). | `34 ABC 123` |
| `turkish_iban` | Valida IBANs Turcos (Prefijo TR + Suma de comprobación Mod-97). | `TR12000...` |
| `tr_id_card_serial`| Valida Números de Serie de Tarjetas de Identidad Nuevas. | `A12F34567` |
| `kep_address` | Valida direcciones de Correo Electrónico Registrado (KEP). | `info@company.kep.tr` |
| `city_code` | Valida Códigos de Placa de Ciudades Turcas (1-81). | `34`, `6`, `81` |

### Ejemplo de Uso

En su Controlador o Form Request:

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

## Servicio de Datos y Direcciones

El paquete incluye un servicio de datos para acceder fácilmente a listas oficiales de ciudades y distritos turcos.

### Uso

```php
use Laravingo\TurkiyeValidator\Facades\Turkiye;

// Obtener todas las ciudades (Código de Placa => Nombre)
$cities = Turkiye::cities(); 
// Devuelve: [1 => 'Adana', ..., 34 => 'İstanbul', ...]

// Obtener distritos para una ciudad (por Código de Placa)
$districts = Turkiye::districts(34); 
// Devuelve: ['Adalar', 'Arnavutköy', 'Ataşehir', ...]
```

---

## Funciones Auxiliares

Las utilidades auxiliares están disponibles a través del facade `Turkiye` para formatear y enmascarar datos sensibles.

### Formato de Teléfono

```php
// La entrada puede estar desordenada: "0532 123 45 67" o "532-123-4567"
$formatted = Turkiye::formatPhoneNumber('0532 123 45 67');

// La salida depende de la configuración 'phone_format':
// 'E164':     "+905321234567" (Por defecto)
// 'NATIONAL': "05321234567"
// 'RAW':      "5321234567"
```

### Enmascaramiento de Identidad

```php
$masked = Turkiye::maskIdentityNumber('12345678901');

// Salida (basada en 'mask_char'): "123******01"
```

---

## Sanitización y Auxiliares

El paquete proporciona una clase `TurkishSanitizer` (y un facade `Turkiye`) para limpiar entradas desordenadas. Maneja explícitamente la conversión correcta de caracteres turcos (i/İ/I/ı), independientemente de la configuración regional del servidor.

### Uso

```php
use Laravingo\TurkiyeValidator\Utilities\TurkishSanitizer;

$sanitizer = new TurkishSanitizer();

// Title Case (Maneja correctamente i/İ/I/ı)
echo $sanitizer->toTitle('i̇stanbul ve IĞDIR'); 
// Salida: "İstanbul Ve Iğdır"

// Limpiar Número de Teléfono (Devuelve 10 dígitos puros)
echo $sanitizer->cleanPhone('0 (555) 123-45 67');
// Salida: "5551234567"

// Limpiar IBAN (Mayúsculas + Sin Espacios)
echo $sanitizer->cleanIban('tr 12 34 56...');
// Salida: "TR123456..."
```

---

## Eloquent Casts (Característica Pro)

Limpia y formatea automáticamente tus datos *antes* de que se guarden en la base de datos utilizando Laravel Custom Casts.

### Uso en el Modelo

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravingo\TurkiyeValidator\Casts\TurkishPhoneCast;
use Laravingo\TurkiyeValidator\Casts\TurkishIbanCast;
use Laravingo\TurkiyeValidator\Casts\TurkishTitleCast;

class User extends Model
{
    protected $casts = [
        'phone'      => TurkishPhoneCast::class, // Auto-limpia a 10 dígitos
        'iban'       => TurkishIbanCast::class,  // Mayúsculas + Sin Espacios
        'full_name'  => TurkishTitleCast::class, // Auto-convierte a Title Case (Lógica Turca)
    ];
}
```

```php
// Ahora, cuando haces $user->phone = '(555) 123'; $user->save();, guarda 555123... en la BD.
```

---

## Proveedor Faker (Pruebas)

Registramos automáticamente un proveedor de Faker para que puedas generar datos de prueba **matemáticamente válidos** en tus factories y seeds.

### Uso

```php
// En un Factory o Seeder
$validId    = fake()->turkishIdNumber();    // Suma de comprobación válida
$validIban  = fake()->turkishIban();        // IBAN TR válido
$validTax   = fake()->turkishTaxIdNumber(); // VKN válido
$validPlate = fake()->turkishLicensePlate();// 06 ABC 123 válido
$validPhone = fake()->turkishPhoneNumber(); // +905... válido
```

---

## Localización

El paquete soporta Inglés (`en`) y Turco (`tr`) desde el primer momento.

Para cambiar el idioma, simplemente configura la configuración regional de tu aplicación Laravel en `config/app.php`:

```php
'locale' => 'tr',
```

Los mensajes de error cambiarán automáticamente a turco (por ejemplo, "Geçerli bir T.C. Kimlik Numarası olmalıdır").

---

## Licencia

La Licencia MIT (MIT). Por favor, consulta el [Archivo de Licencia](https://github.com/laravingo/turkiye-validator/blob/main/LICENSE.md) para más información.

---

## Pruebas

Este paquete utiliza [Pest PHP](https://pestphp.com) para pruebas automatizadas.

```bash
composer test
```
        
Para ejecutar pruebas específicas:
        
```bash
vendor/bin/pest --filter=ValidationRulesTest
```
