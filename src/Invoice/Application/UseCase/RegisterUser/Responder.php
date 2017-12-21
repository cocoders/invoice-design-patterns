<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase\RegisterUser;

use Invoice\Domain\User;

interface Responder
{
    public function userWasRegistered(User $user): void;
}
