<?php

namespace Tests\Invoice\Adapter\Pdo\Domain;

use Invoice\Adapter\Pdo\Domain\User;
use Invoice\Adapter\Pdo\Domain\UserFactory;
use Invoice\Adapter\Pdo\Domain\UserRepository;
use Invoice\Domain\Email;
use Invoice\Domain\PasswordHash;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * @integration
 */
class UserRepositoryTest extends TestCase
{
    /**
     * @var PDO
     */
    private $connection;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserFactory
     */
    private $userFactory;

    public function setUp()
    {
        $this->userRepository = new UserRepository(
            $this->getConnection()
        );
        $this->userFactory = new UserFactory();

        $this->getConnection()->exec('DELETE FROM users');
    }

    public function testThatSaveUserIntoDatabase()
    {
        $user = $this->userFactory->create(
            'leszek.prabucki@gmail.com',
            'test123'
        );
        $this->userRepository->add($user);

        $stmt = $this->getConnection()->query('SELECT * FROM users');
        $users = $stmt->fetchAll();

        self::assertCount(1, $users);
        self::assertEquals('leszek.prabucki@gmail.com', $users[0]['email']);
        self::assertNotNull($user->id());
    }

    public function testThatCheckIfHasUserInDatabase()
    {
        $user = $this->userFactory->create(
            'leszek.prabucki@gmail.com',
            'test123'
        );
        $secondUser = $this->userFactory->create(
            'jan.kowalski@gmail.com',
            'test123'
        );
        $this->userRepository->add($user);

        self::assertTrue($this->userRepository->has($user));
        self::assertFalse($this->userRepository->has($secondUser));
    }

    public function testThatSaveSameUserTwiceDoNotInsertNewRecord()
    {
        $user = $this->userFactory->create(
            'leszek.prabucki@gmail.com',
            'test123'
        );
        $this->userRepository->add($user);
        $this->userRepository->add($user);

        $stmt = $this->getConnection()->query('SELECT * FROM users');
        $users = $stmt->fetchAll();

        self::assertCount(1, $users);
        self::assertEquals('leszek.prabucki@gmail.com', $users[0]['email']);
        self::assertNotNull($user->id());
    }

    private function getConnection(): PDO
    {
        if ($this->connection) {
            return $this->connection;
        }

        $this->connection = new PDO(
            getenv('POSTGRES_DSN'),
            getenv('POSTGRES_USER'),
            getenv('POSTGRES_PASSWORD')
        );
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->connection;
    }
}