<?php

declare(strict_types=1);

namespace Invoice\Domain;

interface Users
{
    public function add(User $user): void;
    public function has(User $user): bool;
}
