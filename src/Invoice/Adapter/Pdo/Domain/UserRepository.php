<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Application\UnitOfWork;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\User;
use Invoice\Domain\UserRepository as UserRepositoryInterface;
use PDO;

final class UserRepository implements UserRepositoryInterface
{
    private $pdo;

    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var UnitOfWork
     */
    private $unitOfWork;

    public function __construct(PDO $pdo, UserFactory $userFactory, UnitOfWork $unitOfWork)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->userFactory = $userFactory;
        $this->unitOfWork = $unitOfWork;
    }

    /**
     * @param Email $email
     * @throws UserNotFound
     * @return User
     */
    public function getByEmail(Email $email): User
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM users WHERE email = :email'
        );
        $stmt->execute([
            'email' => (string) $email
        ]);

        if ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $user = $this->userFactory->createFromStorage($result);
            $this->unitOfWork->scheduleForUpdate($user);

            return $user;
        }

        throw new UserNotFound();
    }

    public function add(User $user): void
    {
        if (!$user instanceof \Invoice\Adapter\Pdo\Domain\User) {
            throw new \InvalidArgumentException(
                sprintf('Only %s class accepted', \Invoice\Adapter\Pdo\Domain\User::class)
            );
        }

        if ($this->unitOfWork->isScheduled($user)) {
            return;
        }
        $this->unitOfWork->scheduleForInsert($user);
    }

    public function has(User $user): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM users WHERE email = :email'
        );
        $stmt->execute([
            'email' => (string) $user->email()
        ]);

        return (bool) $stmt->fetchColumn();
    }
}