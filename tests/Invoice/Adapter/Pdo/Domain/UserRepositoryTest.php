<?php

declare(strict_types=1);

namespace Tests\Invoice\Adapter\Pdo\Domain;

use Invoice\Adapter\Pdo\Domain\UserFactory;
use Invoice\Adapter\Pdo\Domain\UserRepository;
use Invoice\Domain\Email;
use PDO;
use PHPUnit\Framework\TestCase;
use Invoice\Adapter\Pdo\Application\TransactionManager;
use Invoice\Adapter\Pdo\UnitOfWork;
use Invoice\Adapter\Pdo\Domain\User;

/**
 * @integration
 */
class UserRepositoryTest extends TestCase
{
    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var UnitOfWork
     */
    private $unitOfWork;

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
        $this->unitOfWork = new UnitOfWork();
        $this->repository = new UserRepository($this->pdo, $this->unitOfWork);
    }

    public function testThatObjectsAreAddedToUnitOfWork()
    {
        $user = new User((new Email('leszek.prabucki@gmail.com')), password_hash('password', PASSWORD_BCRYPT));
        $this->repository->add($user);

        $this->assertEquals($user, array_pop($this->unitOfWork->objects()));
    }

    public function testThatHasChecksIfUserExisting()
    {
        $user = new User((new Email('leszek.prabucki@gmail.com')), password_hash('password', PASSWORD_BCRYPT));

        $this->assertTrue($this->repository->has($user));
    }
}