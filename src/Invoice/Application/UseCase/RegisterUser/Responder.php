<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase\RegisterUser;

use Invoice\Domain\Email;
use Invoice\Domain\User;

interface Responder
{
    public function userWasRegistered(User $user): void;
    public function userWithSameEmailAlreadyExists(
        Email $email
    ): void;
    public function emailIsEmpty(): void;
    public function emailIsNotValid(): void;
    public function passwordIsNotValid(): void;
}
