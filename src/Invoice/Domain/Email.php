<?php

declare(strict_types=1);

namespace Invoice\Domain;

final class Email
{
    private $email;

    public function __construct(string $email)
    {
        $this->ensureEmailIsValid($email);

        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function ensureEmailIsValid(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("$email is not valid");
        }
    }
}
