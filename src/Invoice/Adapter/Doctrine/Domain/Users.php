<?php

declare(strict_types=1);

namespace Invoice\Adapter\Doctrine\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\User as BaseUser;
use Invoice\Domain\Users as UsersInterface;

final class Users implements UsersInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(BaseUser $user): void
    {
        $this->entityManager->persist($user);
    }

    public function has(BaseUser $user): bool
    {
        return (bool) $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email.email' => (string) $user->email()])
        ;
    }

    public function get(Email $email): BaseUser
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email.email' => (string) $email]);

        if (!$user) {
            throw new UserNotFound();
        }

        return $user;
    }
}
