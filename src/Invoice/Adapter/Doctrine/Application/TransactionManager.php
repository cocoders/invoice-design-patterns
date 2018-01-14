<?php

declare(strict_types=1);

namespace Invoice\Adapter\Doctrine\Application;

use Doctrine\ORM\EntityManagerInterface;
use Invoice\Application\TransactionManager as TransactionManagerInterface;

final class TransactionManager implements TransactionManagerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function begin(): void
    {
        $this->entityManager->beginTransaction();
    }

    public function commit(): void
    {
        $this->entityManager->flush();
        $this->entityManager->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->rollback();
    }
}
