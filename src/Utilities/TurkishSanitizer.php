<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Utilities;

class TurkishSanitizer
{
    public function toUpper(string $value): string
    {
        $value = str_replace(['i', 'ı'], ['İ', 'I'], $value);
        return mb_strtoupper($value, 'UTF-8');
    }

    public function toLower(string $value): string
    {
        $value = str_replace(['İ', 'I'], ['i', 'ı'], $value);
        return mb_strtolower($value, 'UTF-8');
    }

    public function toTitle(string $value): string
    {
        $words = explode(' ', $this->toLower($value));
        
        $titledWords = array_map(function ($word) {
            if (mb_strlen($word, 'UTF-8') === 0) {
                return $word;
            }
            
            $firstChar = mb_substr($word, 0, 1, 'UTF-8');
            $rest = mb_substr($word, 1, null, 'UTF-8');
            
            return $this->toUpper($firstChar) . $this->toLower($rest);
        }, $words);

        return implode(' ', $titledWords);
    }

    public function cleanPhone(string $value): string
    {
        $value = preg_replace('/[^0-9]/', '', $value);

        if (str_starts_with($value, '90')) {
            $value = substr($value, 2);
        }

        if (str_starts_with($value, '0')) {
            $value = substr($value, 1);
        }

        return $value;
    }

    public function cleanIban(string $value): string
    {
        return $this->toUpper(str_replace(' ', '', $value));
    }

    public function cleanNumeric(string $value): string
    {
        return preg_replace('/[^0-9]/', '', $value);
    }
}
