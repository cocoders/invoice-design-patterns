<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domian;

use Invoice\Adapter\Pdo\Domain\User;
use Invoice\Domain\Email;

class UserFactory implements \Invoice\Domain\UserFactory
{
    public function create(string $email, string $password): \Invoice\Domain\User
    {
        $email = new Email($email);

        return new User($email, $password);
    }
}
