<?php

declare(strict_types=1);

namespace Invoice\Domain;

interface UserFactory
{
    public function create(string $email, string $password): User;
}
