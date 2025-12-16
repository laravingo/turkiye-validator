<?php

declare(strict_types=1);

namespace Laravingo\TurkiyeValidator\Faker;

use Faker\Provider\Base;

class TurkiyeProvider extends Base
{
    public function turkishIdNumber(): string
    {
        $digits = [];
        $digits[0] = $this->generator->numberBetween(1, 9);
        for ($i = 1; $i < 9; $i++) {
            $digits[$i] = $this->generator->numberBetween(0, 9);
        }

        $sumOdds = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8];
        $sumEvens = $digits[1] + $digits[3] + $digits[5] + $digits[7];

        $digits[9] = (($sumOdds * 7) - $sumEvens) % 10;
        $digits[10] = ($sumOdds + $sumEvens + $digits[9]) % 10;

        return implode('', $digits);
    }

    public function turkishTaxIdNumber(): string
    {
        $digits = [];
        for ($i = 0; $i < 9; $i++) {
            $digits[$i] = $this->generator->numberBetween(0, 9);
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $tmp = ($digits[$i] + 10 - ($i + 1)) % 10;
            if ($tmp === 9) {
                $sum += 9;
            } else {
                $sum += ($tmp * (pow(2, 10 - ($i + 1)))) % 9;
            }
        }

        $digits[9] = (10 - ($sum % 10)) % 10;

        return implode('', $digits);
    }

    public function turkishPhoneNumber(): string
    {
        $prefixes = ['530', '531', '532', '533', '534', '535', '536', '537', '538', '539', '540', '541', '542', '543', '544', '545', '546', '547', '548', '549', '551', '552', '553', '554', '555', '559', '505', '506', '507', '501'];
        $prefix = $this->generator->randomElement($prefixes);

        return '+90'.$prefix.$this->generator->numerify('#######');
    }

    public function turkishLicensePlate(): string
    {
        $cityCode = $this->generator->numberBetween(1, 81);
        $letters = $this->generator->toUpper($this->generator->lexify('???'));
        $numbers = $this->generator->numerify('###');

        return sprintf('%02d %s %s', $cityCode, $letters, $numbers);
    }

    public function turkishIban(): string
    {
        $bankCode = $this->generator->numerify('#####');
        $reserve = '0';
        $account = $this->generator->numerify('################');

        $tempIban = $bankCode.$reserve.$account.'2927'.'00';

        $checksum = 98 - (int) bcmod($tempIban, '97');

        return 'TR'.sprintf('%02d', $checksum).$bankCode.$reserve.$account;
    }

    public function turkishIdCardSerial(): string
    {
        $letter1 = $this->generator->regexify('[A-Z]{1}');
        $digits1 = $this->generator->numerify('##');
        $letter2 = $this->generator->regexify('[A-Z]{1}');
        $digits2 = $this->generator->numerify('#####');

        return $letter1.$digits1.$letter2.$digits2;
    }

    public function kepAddress(): string
    {
        return $this->generator->userName.'@hs01.kep.tr';
    }
}
