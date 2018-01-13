<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Domain;

use Invoice\Adapter\Pdo\Domain\User;
use Invoice\Adapter\Pdo\UnitOfWork;
use Invoice\Domain\Email;
use Invoice\Domain\Users;

class UserRepository implements Users
{
    /**
     * @var \PDO
     */
    private $pdo;
    /**
     * @var UnitOfWork
     */
    private $unitOfWork;

    public function __construct(\PDO $pdo, UnitOfWork $unitOfWork)
    {
        $this->pdo = $pdo;
        $this->unitOfWork = $unitOfWork;
    }

    public function add(\Invoice\Domain\User $user): void
    {
        $this->unitOfWork->attach($user);
    }

    public function has(\Invoice\Domain\User $user): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT u.id FROM users AS u where u.id = :id LIMIT 1'
        );

        $stmt->execute([
            'id' => $user->id()
        ]);

        return (bool) $stmt->fetch();
    }
}
