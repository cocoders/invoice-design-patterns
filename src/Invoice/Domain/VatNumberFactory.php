<?php

declare(strict_types=1);

namespace Invoice\Domain;

use Invoice\Domain\Exception\VatNumberNotValid;

interface VatNumberFactory
{
    /**
     * @throws VatNumberNotValid
     */
    public function create(string $number): VatNumber;
}
