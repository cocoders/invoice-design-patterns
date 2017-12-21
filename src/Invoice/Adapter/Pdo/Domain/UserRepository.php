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

    /**
     * @var UserFactory
     */
    private $userFactory;

    public function __construct(PDO $pdo, UserFactory $userFactory)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->userFactory = $userFactory;
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
            return $this->userFactory->createFromStorage($result);
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

        if ($user->id()) {
            $stmt = $this->pdo->prepare('UPDATE users SET name = :name, vat = :vat, address = :address
            WHERE id = :id');

            $profile = $user->profile();
            $stmt->execute([
                'name' => $profile->name(),
                'vat' => (string) $profile->vatIdNumber(),
                'address' => $profile->address(),
                'id' => $user->id()
            ]);
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