<?php

declare(strict_types=1);

namespace Invoice\Domain;

use Invoice\Domain\Exception\EmailIsEmpty;
use Invoice\Domain\Exception\EmailIsNotValid;

class Email
{
    private $email;

    public function __construct(string $email)
    {
        if (!$email) {
            throw new EmailIsEmpty();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new EmailIsNotValid();
        }

        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
