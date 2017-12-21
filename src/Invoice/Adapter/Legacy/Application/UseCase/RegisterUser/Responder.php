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
        header('Location: /login.php?successRegister=1');
        exit;
    }

    public function userWithSameEmailAlreadyExists(
        Email $email
    ): void
    {
        $this->errors['email'] = 'User with given email exists already.';
    }

    public function emailIsEmpty(): void
    {
        $this->errors['email'] = 'Email field was empty.';
    }

    public function emailIsNotValid(): void
    {
        $this->errors['email'] = 'Email is not valid.';
    }

    public function passwordIsNotValid(): void
    {
        $this->errors['password'] = 'Password field was empty.';
    }
}