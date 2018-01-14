<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase\EditProfile;

final class Command
{
    private $vatNumber;
    private $name;
    private $address;
    private $email;

    public function __construct(string $email, string $vatNumber, string $name, string $address)
    {
        $this->vatNumber = $vatNumber;
        $this->name = $name;
        $this->address = $address;
        $this->email = $email;
    }

    public function vatNumber(): string
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

    public function email(): string
    {
        return $this->email;
    }
}
