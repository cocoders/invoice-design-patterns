<?php

declare(strict_types=1);

namespace Invoice\Domain;

class User
{
    private $email;
    private $passwordHash;

    public function __construct(Email $email, PasswordHash $passwordHash)
    {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): PasswordHash
    {
        return $this->passwordHash;
    }
}