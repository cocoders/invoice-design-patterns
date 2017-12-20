<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Domain\Email;
use Invoice\Domain\PasswordHash;
use Invoice\Domain\User;
use Invoice\Domain\UserFactory as UserFactoryInterface;

final class UserFactory implements UserFactoryInterface
{
    public function create(string $email, string $password): User
    {
        return new \Invoice\Adapter\Pdo\Domain\User(
            new Email($email),
            PasswordHash::fromPlainPassword($password)
        );
    }
}