<?php

declare(strict_types=1);

namespace Invoice\Domain;

class User
{
    private $email;
    private $passwordHash;
    private $profile;

    public function __construct(Email $email, PasswordHash $passwordHash, Profile $profile)
    {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->profile = $profile;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): PasswordHash
    {
        return $this->passwordHash;
    }

    public function changeProfile(Profile $profile)
    {
        $this->profile = $profile;
    }

    public function profile(): Profile
    {
        return $this->profile;
    }
}