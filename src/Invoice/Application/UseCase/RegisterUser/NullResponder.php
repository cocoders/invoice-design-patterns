<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase\RegisterUser;

use Invoice\Domain\User;

final class NullResponder implements Responder
{
    public function userAlreadyExists(User $user): void
    {
    }

    public function userRegistered(User $user): void
    {
    }

    public function emailIsEmpty(): void
    {
    }
}
