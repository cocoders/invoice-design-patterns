<?php

declare(strict_types=1);

namespace Invoice\Adapter\Legacy\Application\UseCase\EditProfile;

use Invoice\Application\UseCase\EditProfile\Responder as ResponderInterface;
use Invoice\Domain\Email;
use Invoice\Domain\User;

final class Responder implements ResponderInterface
{
    public function userEditedSuccesfully(User $user): void
    {
        header('Location: /index.php?page=user-profile&successMessage="Profile data updated successfully"');
        exit;
    }

    public function userNotFound(Email $email): void
    {
        throw new \InvalidArgumentException(
            sprintf("User with %s email not found", $email)
        );
    }
}