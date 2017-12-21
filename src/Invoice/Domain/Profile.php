<?php

declare(strict_types=1);

namespace Invoice\Domain;

class Profile
{
    private $name;
    private $idNumber;
    private $address;

    public function __construct(string $name, VatIdNumber $idNumber, string $address)
    {
        $this->name = $name;
        $this->idNumber = $idNumber;
        $this->address = $address;
    }

    public static function defaultProfile(): Profile
    {
        return new Profile(
            '',
            VatIdNumber::empty(),
            ''
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function vatIdNumber(): VatIdNumber
    {
        return $this->idNumber;
    }

    public function address(): string
    {
        return $this->address;
    }
}
