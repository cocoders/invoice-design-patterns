<?php

declare(strict_types=1);

namespace Tests\Invoice\Adapter\Pdo\Domain;

use Invoice\Adapter\Pdo\Application\TransactionManager;
use Invoice\Adapter\Pdo\Domain\Users;
use Invoice\Domain\Email;
use Invoice\Adapter\Pdo\UnitOfWork;
use Invoice\Adapter\Pdo\Domain\User;
use Tests\Invoice\DbTestCase;

/**
 * @integration
 */
class UsersTest extends DbTestCase
{
    /**
     * @var Users
     */
    private $repository;
    /**
     * @var UnitOfWork
     */
    private $unitOfWork;
    /**
     * @var TransactionManager
     */
    private $transactionManager;

    public function setUp()
    {
        parent::setUp();
        $this->unitOfWork = new UnitOfWork();
        $this->transactionManager = new TransactionManager($this->pdo, $this->unitOfWork);
        $this->repository = new Users($this->pdo, $this->unitOfWork);
    }

    public function testThatObjectsAreAddedToUnitOfWork()
    {
        $user = new User((new Email('leszek.prabucki@gmail.com')), password_hash('password', PASSWORD_BCRYPT));
        $this->repository->add($user);

        self::assertEquals($user, array_pop($this->unitOfWork->objects()));
    }

    public function testThatHasChecksIfUserExisting()
    {
        $existingUser = new User((new Email('leszek.prabucki@gmail.com')), password_hash('password', PASSWORD_BCRYPT));
        $this->repository->add($existingUser);
        $newUser = new User((new Email('new@gmail.com')), password_hash('password', PASSWORD_BCRYPT));

        self::assertTrue($this->repository->has($existingUser));
        self::assertFalse($this->repository->has($newUser));
    }

    public function testThatHasChecksIfUserExistingAfterCommitTransaction()
    {
        $existingUser = new User((new Email('leszek.prabucki@gmail.com')), password_hash('password', PASSWORD_BCRYPT));
        $this->transactionManager->begin();
        $this->repository->add($existingUser);
        $this->transactionManager->commit();
        $newUser = new User((new Email('new@gmail.com')), password_hash('password', PASSWORD_BCRYPT));

        self::assertTrue($this->repository->has($existingUser));
        self::assertFalse($this->repository->has($newUser));
    }
}
