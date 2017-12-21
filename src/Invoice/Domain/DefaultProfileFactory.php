<?php

declare(strict_types=1);

namespace Invoice\Domain;

final class DefaultProfileFactory implements ProfileFactory
{
    public function create(
        string $name,
        string $vatNumber,
        string $address
    ): Profile {
        return new Profile(
            $name,
            $vatNumber ? VatIdNumber::polish($vatNumber) : VatIdNumber::empty(),
            $address
        );
    }

    public function defaultProfile(): Profile
    {
        return new Profile(
            '',
            VatIdNumber::empty(),
            ''
        );
    }
}