<?php

declare(strict_types=1);

namespace Invoice\Domain;

use Invoice\Domain\Exception\ProfileNotFound;
use Invoice\Domain\User\Profile;

class User
{
    protected $email;
    protected $passwordHash;

    /**
     * @var Profile|null
     */
    private $profile;

    public function __construct(Email $email, string $passwordHash)
    {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function changeProfile(Profile $profile): void
    {
        $this->profile = $profile;
    }

    public function hasProfile(): bool
    {
        return (bool) $this->profile;
    }

    public function profile(): Profile
    {
        if (!$this->profile) {
            throw new ProfileNotFound('Profile not found, please call hasProfile to check if profile was set');
        }

        return $this->profile;
    }
}
