<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\User;
use Invoice\Domain\UserRepository as UserRepositoryInterface;
use PDO;

final class UserRepository implements UserRepositoryInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param Email $email
     * @throws UserNotFound
     * @return User
     */
    public function getByEmail(Email $email): User
    {
        // TODO: Implement getByEmail() method.
    }

    public function add(User $user): void
    {
        if (!$user instanceof \Invoice\Adapter\Pdo\Domain\User) {
            throw new \InvalidArgumentException(
                sprintf('Only %s class accepted', \Invoice\Adapter\Pdo\Domain\User::class)
            );
        }

        if ($user->id()) {
            //tutaj update
            return;
        }
        $stmt = $this->pdo->prepare('INSERT INTO users (email, password_hash)
          VALUES (:email, :password)');

        $stmt->execute([
            'email' => (string) $user->email(),
            'password' => (string) $user->password()
        ]);

        $user->setId((int) $this->pdo->lastInsertId());
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