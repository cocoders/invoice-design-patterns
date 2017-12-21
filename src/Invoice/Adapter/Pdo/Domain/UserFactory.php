<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Domain\Email;
use Invoice\Domain\Exception\EmailIsEmpty;
use Invoice\Domain\Exception\EmailIsNotValid;
use Invoice\Domain\PasswordHash;
use Invoice\Domain\ProfileFactory;
use Invoice\Domain\User;
use Invoice\Domain\UserFactory as UserFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserFactory implements UserFactoryInterface
{
    private $profileFactory;

    public function __construct(ProfileFactory $profileFactory)
    {
        $this->profileFactory = $profileFactory;
    }

    public function create(string $email, string $password): User
    {
        return new \Invoice\Adapter\Pdo\Domain\User(
            new Email($email),
            PasswordHash::fromPlainPassword($password),
            $this->profileFactory->defaultProfile()
        );
    }

    /**
     * @throws EmailIsEmpty
     * @throws EmailIsNotValid
     * @return User
     */
    public function createFromStorage(array $array): User
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setRequired([
            'id',
            'name',
            'vat',
            'password_hash',
            'address',
            'email'
        ]);

        $array = $optionResolver->resolve($array);

        $user = new \Invoice\Adapter\Pdo\Domain\User(
            new Email($array['email']),
            PasswordHash::fromHashedPassword($array['password_hash']),
            $this->profileFactory->create(
                (string) $array['name'],
                (string) $array['vat'],
                (string) $array['address']
            )
        );
        $user->setId((int) $array['id']);

        return $user;
    }
}