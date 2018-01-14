<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo\Application;

use Invoice\Adapter\Pdo\Domain\User;
use Invoice\Adapter\Pdo\UnitOfWork;
use Invoice\Application\TransactionManager as TransactionManagerInterface;
use PDO;

class TransactionManager implements TransactionManagerInterface
{
    private $pdo;
    private $unitOfWork;

    public function __construct(PDO $pdo, UnitOfWork $unitOfWork)
    {
        $this->pdo = $pdo;
        $this->unitOfWork = $unitOfWork;
    }

    public function begin(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        foreach ($this->unitOfWork->objects() as $object) {
            if ($object instanceof User && $object->id()) {
                $stmt = $this->pdo->prepare(
                    'UPDATE users SET email = :email, vat = :vat, address = :address, name = :name WHERE id = :id'
                );
                $stmt->execute([
                    'email' => (string) $object->email(),
                    'vat' => $object->hasProfile() ? $object->profile()->vatNumber() : '',
                    'address' => $object->hasProfile() ? $object->profile()->address() : '',
                    'name' => $object->hasProfile() ? $object->profile()->name() : '',
                    'id' => $object->id()
                ]);
            }
            if ($object instanceof User && !$object->id()) {
                $stmt = $this->pdo->prepare(
                    'INSERT INTO users(email, password_hash) VALUES (:email, :password_hash)'
                );
                $stmt->execute([
                    'email' => (string) $object->email(),
                    'password_hash' => $object->passwordHash()
                ]);

                $object->setId((int) $this->pdo->lastInsertId());
            }
        }

        $this->pdo->commit();
        $this->unitOfWork->clear();
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
        $this->unitOfWork->clear();
    }
}
