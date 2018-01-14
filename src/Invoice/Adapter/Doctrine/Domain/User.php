<?php

declare(strict_types=1);

namespace Invoice\Adapter\Doctrine\Domain;

use Invoice\Domain\User as BaseUser;

class User extends BaseUser
{
    private $id;
}
