<?php

declare(strict_types=1);

namespace Invoice\Domain;

use Invoice\Domain\Exception\UserNotFound;

interface UserRepository
{
    public function add(User $user): void;
    public function has(User $user): bool;

    /**
     * @param Email $email
     * @throws UserNotFound
     * @return User
     */
    public function getByEmail(Email $email): User;
}
