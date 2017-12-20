<?php

declare(strict_types=1);

namespace Invoice\Domain;

class User
{
    private $email;
    private $passwordHash;

    public function __construct(Email $email, string $passwordHash)
    {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->passwordHash;
    }
}