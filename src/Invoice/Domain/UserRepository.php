<?php

declare(strict_types=1);

namespace Invoice\Domain;

interface UserRepository
{
    public function add(User $user): void;
    public function has(User $user): bool;
}
