<?php

declare(strict_types=1);

namespace Invoice\Adapter\Legacy\Domain;

use Invoice\Domain\Exception\VatNumberNotValid;
use Invoice\Domain\VatNumber;
use Invoice\Domain\VatNumberFactory as VatNumberFactoryInterface;

final class VatNumberFactory implements VatNumberFactoryInterface
{
    /**
     * @throws VatNumberNotValid
     */
    public function create(string $number): VatNumber
    {
        $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';

        if (strpos(strtolower($acceptLanguage), 'pl-pl') || strtolower($acceptLanguage) === 'pl') {
            return VatNumber::polish($number);
        }

        return VatNumber::fromString($number);
    }
}
