<?php

namespace Invoice\Application\UseCase\EditProfile;

use Invoice\Domain\Email;
use Invoice\Domain\User;

interface Responder
{
    public function userEditedSuccesfully(User $user): void;
    public function userNotFound(Email $email): void;
}
