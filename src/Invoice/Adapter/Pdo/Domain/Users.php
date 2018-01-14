<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Adapter\Pdo\UnitOfWork;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\User as BaseUser;
use Invoice\Domain\Users as UsersInterface;
use Invoice\Domain\VatNumber;
use PDO;

final class Users implements UsersInterface
{
    private $unitOfWork;
    private $pdo;

    public function __construct(PDO $pdo, UnitOfWork $unitOfWork)
    {
        $this->unitOfWork = $unitOfWork;
        $this->pdo = $pdo;
    }

    /**
     * @param User $user
     */
    public function add(BaseUser $user): void
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException(sprintf('Expected %s object', User::class));
        }

        $this->unitOfWork->attach($user);
    }

    /**
     * @param User $user
     */
    public function has(BaseUser $user): bool
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException(sprintf('Expected %s object', User::class));
        }

        if ($this->unitOfWork->has($user)) {
            return true;
        }

        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute(['email' => $user->email()]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * @throws UserNotFound
     */
    public function get(Email $email): BaseUser
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => (string) $email]);

        $result = $stmt->fetch();

        if (!$result) {
            throw new UserNotFound();
        }

        $user = User::fromDatabase(
            (int) $result['id'],
            (string) $result['email'],
            (string) $result['password_hash'],
            (string) $result['vat'],
            (string) $result['name'],
            (string) $result['address']
        );
        $this->unitOfWork->attach($user);

        return $user;
    }
}
