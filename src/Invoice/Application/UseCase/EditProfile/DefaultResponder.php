<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase\EditProfile;

use Invoice\Domain\Email;
use Invoice\Domain\User;

class DefaultResponder implements Responder
{
    public function userEditedSuccesfully(User $user): void
    {
    }

    public function userNotFound(Email $email): void
    {
        throw new \InvalidArgumentException(
            sprintf('User with %s email not found', $email)
        );
    }
}