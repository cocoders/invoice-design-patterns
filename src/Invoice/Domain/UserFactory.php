<?php

declare(strict_types=1);

namespace Invoice\Domain;

use Invoice\Domain\Exception\EmailIsEmpty;
use Invoice\Domain\Exception\EmailIsNotValid;
use Invoice\Domain\Exception\PasswordIsNotValid;

interface UserFactory
{
    /**
     * @throws EmailIsEmpty
     * @throws EmailIsNotValid
     * @throws PasswordIsNotValid
     * @return User
     */
    public function create(string $email, string $password): User;
}
