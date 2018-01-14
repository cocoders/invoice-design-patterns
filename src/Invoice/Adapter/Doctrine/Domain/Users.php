<?php

declare(strict_types=1);

namespace Invoice\Adapter\Doctrine\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\User as BaseUser;
use Invoice\Domain\Users as UsersInterface;

final class Users extends EntityRepository implements UsersInterface
{
    public function __construct(EntityManagerInterface $entityManager, ClassMetadata $metadata)
    {
        parent::__construct($entityManager, $metadata);
    }

    public function add(BaseUser $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function has(BaseUser $user): bool
    {
        return (bool) $this->findOneBy(['email.email' => (string) $user->email()]);
    }

    public function get(Email $email): BaseUser
    {
        /** @var User $result */
        $user = $this->findOneBy(['email.email' => $email]);

        if (!$user) {
            throw new UserNotFound();
        }

        return $user;
    }
}
