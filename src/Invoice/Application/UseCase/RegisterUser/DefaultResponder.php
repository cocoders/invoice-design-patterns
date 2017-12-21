<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase\RegisterUser;

use Invoice\Domain\Email;
use Invoice\Domain\User;

final class DefaultResponder implements Responder
{
    public function userWasRegistered(User $user): void
    {
    }

    public function userWithSameEmailAlreadyExists(
        Email $email
    ): void
    {
    }
}