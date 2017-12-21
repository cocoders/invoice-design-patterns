<?php

declare(strict_types=1);

namespace Invoice\Adapter\Legacy\Application\UseCase\RegisterUser;

use Invoice\Application\UseCase\RegisterUser\Responder as ResponderInterface;
use Invoice\Domain\Email;
use Invoice\Domain\User;

final class Responder implements ResponderInterface
{
    private $errors;

    public function __construct(Errors $errors)
    {
        $this->errors = $errors;
    }

    public function userWasRegistered(User $user): void
    {
    }

    public function userWithSameEmailAlreadyExists(
        Email $email
    ): void
    {
    }

    public function emailIsEmpty(): void
    {
        $this->errors['email'] = 'Email field was empty.';
    }

    public function emailIsNotValid(): void
    {
    }

    public function passwordIsNotValid(): void
    {
    }
}