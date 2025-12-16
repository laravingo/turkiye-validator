# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-12-16

### Added
- **Validation Rules**:
    - `turkish_id`: Validates T.C. Identity Number (Standard sets of algorithms).
    - `turkish_phone`: Validates Turkish Phone Numbers (+90/0/None prefix support).
    - `tax_id`: Validates Tax Identification Number (VKN) using Mod-10 algorithm.
    - `license_plate`: Validates Turkish License Plates (City codes 01-81).
    - `turkish_iban`: Validates Turkish IBANs with Mod-97 checksum.
    - `tr_id_card_serial`: Validates new T.C. Identity Card Serial Numbers.
    - `kep_address`: Validates Registered Electronic Mail (KEP) addresses.
- **Sanitization**:
    - `TurkishSanitizer` utility class.
    - `TurkishHelper` class for general formatting.
    - Methods to convert strings to Title/Upper/Lower case with proper Turkish character support (i/İ/I/ı).
    - Methods to clean Phone Numbers and IBANs.
- **Eloquent Casts**:
    - `TurkishPhoneCast`: Auto-formats phone numbers on model save.
    - `TurkishIbanCast`: Auto-uppercases and cleans IBANs.
    - `TurkishTitleCast`: Auto-capitalizes names with Turkish rules.
- **Usage Helper**:
    - `Turkiye` Facade for easy access to helper methods.
- **Localization**:
    - Full support for English (`en`) and Turkish (`tr`) validation error messages.
- **Testing**:
    - `Faker` provider (`TurkiyeProvider`) integration for generating valid test data (IDs, IBANs, Plates, etc.).
    - Automated Unit Tests using **Pest PHP**.
- **DevOps**:
    - GitHub Actions workflow (`run-tests.yml`) for matrix testing across PHP 8.2/8.3 and Laravel 10/11/12.
