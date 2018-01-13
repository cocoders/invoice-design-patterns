<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Domain\Email;
use Invoice\Domain\User as BaseUser;

final class User extends BaseUser
{
    private $id;

    public function __construct(Email $email, string $passwordHash)
    {
        parent::__construct($email, $passwordHash);
    }

    public static function fromDatabase(Email $email, string $passwordHash, int $id): User
    {
        $user = new User($email, $passwordHash);
        $user->id = $id;

        return $user;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function id(): ?int
    {
        return $this->id;
    }
}
