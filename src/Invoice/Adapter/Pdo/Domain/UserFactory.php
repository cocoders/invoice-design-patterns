<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Domain\Email;
use Invoice\Domain\User as BaseUser;
use Invoice\Domain\UserFactory as UserFactoryInterface;

class UserFactory implements UserFactoryInterface
{
    /**
     * @return User
     */
    public function create(string $email, string $password): BaseUser
    {
        return new User(
            new Email($email),
            $password
        );
    }
}
