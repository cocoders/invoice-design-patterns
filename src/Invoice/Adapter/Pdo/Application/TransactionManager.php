<?php

namespace Invoice\Adapter\Pdo\Application;


use Invoice\Adapter\Pdo\Domain\User;
use Invoice\Application\TransactionManager as TransactionManagerInterface;
use Invoice\Application\UnitOfWork;

final class TransactionManager implements TransactionManagerInterface
{
    private $pdo;
    private $unitOfWork;

    public function __construct(\PDO $pdo, UnitOfWork $unitOfWork)
    {
        $this->pdo = $pdo;
        $this->unitOfWork = $unitOfWork;
    }

    public function begin()
    {
        $this->pdo->beginTransaction();
        $this->unitOfWork->clear();
    }

    public function commit()
    {
        foreach ($this->unitOfWork->objectsForUpdate() as $object)
        {
            if ($object instanceof User && $object->isChanged()) {
                $stmt = $this->pdo->prepare('UPDATE users SET name = :name, vat = :vat, address = :address
            WHERE id = :id');

                $profile = $object->profile();
                $stmt->execute([
                    'name' => $profile->name(),
                    'vat' => (string) $profile->vatIdNumber(),
                    'address' => $profile->address(),
                    'id' => $object->id()
                ]);
            }
        }

        foreach ($this->unitOfWork->objectsForInsert() as $object) {
            if ($object instanceof User) {
                $stmt = $this->pdo->prepare('INSERT INTO users (email, password_hash)
          VALUES (:email, :password)');

                $stmt->execute([
                    'email' => (string) $object->email(),
                    'password' => (string) $object->password()
                ]);

                $object->setId((int) $this->pdo->lastInsertId());
            }
        }

        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }

        $this->unitOfWork->clear();
    }

    public function rollback()
    {
        $this->pdo->rollBack();
        $this->unitOfWork->clear();
    }
}