<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Domain\Email;
use Invoice\Domain\User as BaseUser;
use Invoice\Domain\VatNumber;

final class User extends BaseUser
{
    private $id;

    public function __construct(Email $email, string $passwordHash)
    {
        parent::__construct($email, $passwordHash);
    }

    public static function fromDatabase(
        int $id,
        string $email,
        string $passwordHash,
        string $vat,
        string $name,
        string $address
    ): User {
        $user = new User(new Email($email), $passwordHash);
        $user->id = $id;

        if ($vat || $name || $address) {
            $user->changeProfile(new BaseUser\Profile(
                VatNumber::fromString($vat),
                $name,
                $address
            ));
        }

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
