<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Domain\Profile;
use Invoice\Domain\User as BaseUser;

class User extends BaseUser
{
    private $id;
    private $isChanged = false;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function id(): ?int
    {
        return $this->id;
    }


    public function changeProfile(Profile $profile)
    {
        parent::changeProfile($profile);

        $this->isChanged = true;
    }

    public function isChanged()
    {
        return $this->isChanged;
    }
}