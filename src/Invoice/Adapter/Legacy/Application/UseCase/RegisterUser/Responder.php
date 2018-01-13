<?php

declare(strict_types=1);

namespace Invoice\Adapter\Legacy\Application\UseCase\RegisterUser;

use Invoice\Adapter\Legacy\Application\Errors;
use Invoice\Application\UseCase\RegisterUser\Responder as ResponderInterface;
use Invoice\Domain\User;

final class Responder implements ResponderInterface
{
    private $errors;

    public function __construct(Errors $errors)
    {
        $this->errors = $errors;
    }

    public function userAlreadyExists(User $user): void
    {
        $this->errors->addError('email', 'User with given email exists already.');
    }

    public function userRegistered(User $user): void
    {
    }

    public function emailIsEmpty(): void
    {
        $this->errors->addError('email', 'Email is empty');
    }
}
