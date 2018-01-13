<?php

declare(strict_types=1);

namespace Tests\Invoice\Adapter\Pdo\Application;

use Invoice\Domain\Email;
use Invoice\Adapter\Pdo\Application\TransactionManager;
use Invoice\Adapter\Pdo\UnitOfWork;
use Invoice\Adapter\Pdo\Domain\User;
use Tests\Invoice\DbTestCase;

/**
 * @integration
 */
class TransactionManagerTest extends DbTestCase
{
    public function testThatCommitCreateNewRecordInDatabase()
    {
        $user = new User(
            new Email('leszek.prabucki@gmail.com'),
            password_hash('password', PASSWORD_BCRYPT)
        );
        $unitOfWork = new UnitOfWork();
        $unitOfWork->attach($user);

        $transactionManager = new TransactionManager(
            $this->pdo,
            $unitOfWork
        );
        $transactionManager->begin();
        $transactionManager->commit();

        $stmt = $this
            ->pdo
            ->query('SELECT * FROM users');

        $users = $stmt->fetchAll();

        self::assertCount(1, $users);
        self::assertEquals('leszek.prabucki@gmail.com', $users[0]['email']);
        self::assertNotEmpty($user->id());
    }
}
