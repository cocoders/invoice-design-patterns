<?php

declare(strict_types=1);

namespace Invoice\Domain;

use Invoice\Domain\Exception\UserNotFound;

interface Users
{
    public function add(User $user): void;
    public function has(User $user): bool;

    /**
     * @throws UserNotFound
     */
    public function get(Email $email): User;
}
