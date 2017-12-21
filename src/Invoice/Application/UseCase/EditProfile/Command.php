<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase\EditProfile;

class Command
{
    private $name;
    private $vatIdNumber;
    private $address;
    private $email;

    public function __construct(string $email, string $name, string $vatIdNumber, string $address)
    {
        $this->name = $name;
        $this->vatIdNumber = $vatIdNumber;
        $this->address = $address;
        $this->email = $email;
    }

    public function vatIdNumber(): string
    {
        return $this->vatIdNumber;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function address(): string
    {
        return $this->address;
    }

    public function name(): string
    {
        return $this->name;
    }
}