<?php

declare(strict_types=1);

namespace Invoice\Domain;

interface ProfileFactory
{
    public function create(string $name, string $vatNumber, string $address): Profile;
    public function defaultProfile(): Profile;
}
