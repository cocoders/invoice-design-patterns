<?php

declare(strict_types=1);

namespace Tests\Invoice\Adapter\Pdo\Application;

use Invoice\Domain\Email;
use PDO;
use PHPUnit\Framework\TestCase;
use Invoice\Adapter\Pdo\Application\TransactionManager;
use Invoice\Adapter\Pdo\UnitOfWork;
use Invoice\Adapter\Pdo\Domain\User;

/**
 * @integration
 */
class TransactionManagerTest extends TestCase
{
    /**
     * @var PDO
     */
    private $pdo;

    public function setUp()
    {
        if (!$this->pdo) {
            $this->pdo = new PDO(
                getenv('POSTGRES_DSN'),
                getenv('POSTGRES_USER'),
                getenv('POSTGRES_PASSWORD')
            );
        }

        $this->pdo->exec('DELETE FROM users');
    }

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