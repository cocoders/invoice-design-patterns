<?php

declare(strict_types=1);

namespace Invoice\Domain;

use Invoice\Domain\Exception\VatNumberNotValid;

final class VatNumber
{
    private $number = '';

    private function __construct()
    {
    }

    public static function polish(string $number): VatNumber
    {
        if (self::isValidPolishVatNumber($number)) {
            $vatNumber = new VatNumber();
            $vatNumber->number = $number;

            return $vatNumber;
        }

        throw new VatNumberNotValid($number);
    }

    public static function fromString(string $number): VatNumber
    {
        $vatNumber = new VatNumber();
        $vatNumber->number = $number;

        return $vatNumber;
    }

    private static function isValidPolishVatNumber(string $number): bool
    {
        $number = preg_replace('/[^0-9]+/', '', $number);
        if (strlen($number) !== 10) {
            return false;
        }
        $weigths = [6, 5, 7, 2, 3, 4, 5, 6, 7];
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) ($weigths[$i] * $number[$i]);
        }
        $int = $sum % 11;
        $controlNr = $int === 10 ? 0 : (int) $int;

        return ((int) $controlNr) === ((int) $number[9]);
    }

    public function __toString(): string
    {
        return (string) $this->number;
    }
}
