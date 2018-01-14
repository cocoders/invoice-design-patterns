<?php

declare(strict_types=1);

namespace Invoice\Application\UseCase\EditProfile;

use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\User\Profile;

final class NullResponder implements Responder
{
    public function userNotFound(UserNotFound $userNotFound): void
    {
        throw $userNotFound;
    }

    public function profileChanged(Email $email, Profile $profile): void
    {
    }
}
