<?php

declare(strict_types=1);

namespace Invoice\Domain;

use Invoice\Domain\Exception\PasswordIsNotValid;

class PasswordHash
{
    private $hash;

    private function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public static function fromHashedPassword(string $hash): PasswordHash
    {
        return new PasswordHash($hash);
    }

    public static function fromPlainPassword(string $password): PasswordHash
    {
        if (!$password) {
            throw new PasswordIsNotValid();
        }

        return new PasswordHash(password_hash($password, PASSWORD_BCRYPT));
    }

    public function __toString(): string
    {
        return $this->hash;
    }
}