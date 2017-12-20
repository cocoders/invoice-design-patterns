<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Domain\User as BaseUser;

class User extends BaseUser
{
    private $id;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function id(): ?int
    {
        return $this->id;
    }
}