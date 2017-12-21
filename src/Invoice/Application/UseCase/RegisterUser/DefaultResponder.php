<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase\RegisterUser;

use Invoice\Domain\User;

final class DefaultResponder implements Responder
{
    public function userWasRegistered(User $user): void
    {
    }
}