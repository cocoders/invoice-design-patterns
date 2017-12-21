<?php

declare(strict_types=1);

namespace Invoice\Domain;

use Invoice\Domain\Exception\VatIdNumberIsEmpty;
use Invoice\Domain\Exception\VatIdNumberIsNotValid;

class VatIdNumber
{
    private $number;

    private function __construct()
    {
        $this->number = '';
    }

    public static function empty(): VatIdNumber
    {
        return new VatIdNumber();
    }

    public static function polish(string $number): VatIdNumber
    {
        $vatIdNumber = new VatIdNumber();

        if (!$number) {
            throw new VatIdNumberIsEmpty();
        }
        if (!self::isPolishNipValid($number)) {
            throw new VatIdNumberIsNotValid(
                sprintf(
                    'Polish checksum for this %s number is not valid',
                    $number
                )
            );
        }
        $vatIdNumber->number = $number;

        return $vatIdNumber;
    }

    private static function isPolishNipValid(string $number): bool
    {
        $number = preg_replace('/[^0-9]+/', '', $number);

        if (strlen($number) !== 10) {
            return false;
        }

        $weigths = [6, 5, 7, 2, 3, 4, 5, 6, 7];
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += $weigths[$i] * $number[$i];
        }

        $int = $sum % 11;
        $controlNr = $int === 10 ? 0 : $int;

        return $controlNr == $number[9];
    }

    public function __toString(): string
    {
        return $this->number;
    }
}