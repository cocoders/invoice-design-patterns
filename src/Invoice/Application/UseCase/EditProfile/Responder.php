<?php

namespace Invoice\Application\UseCase\EditProfile;

interface Responder
{

    public function userEditedSuccesfully($argument1);

    public function userNotFound($argument1);
}
