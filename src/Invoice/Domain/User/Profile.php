<?php

declare(strict_types=1);

namespace Invoice\Domain\User;

use Invoice\Domain\VatNumber;

final class Profile
{
    private $vatNumber;
    private $name;
    private $address;

    public function __construct(VatNumber $vatNumber, string $name, string $address)
    {
        $this->vatNumber = $vatNumber;
        $this->name = $name;
        $this->address = $address;
    }

    public function vatNumber(): VatNumber
    {
        return $this->vatNumber;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function address(): string
    {
        return $this->address;
    }
}
