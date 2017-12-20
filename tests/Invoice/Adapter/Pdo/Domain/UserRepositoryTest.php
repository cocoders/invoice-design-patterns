<?php

namespace Tests\Invoice\Adapter\Pdo\Domain;

use Invoice\Adapter\Pdo\Domain\User;
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

    public function setUp()
    {
        $this->userRepository = new UserRepository(
            $this->getConnection()
        );

        $this->getConnection()->exec('DELETE FROM users');
    }

    public function testThatSaveUserIntoDatabase()
    {
        $user = new User(
            new Email('leszek.prabucki@gmail.com'),
            PasswordHash::fromPlainPassword('test123')
        );
        $this->userRepository->add($user);

        $stmt = $this->getConnection()->query('SELECT * FROM users');
        $users = $stmt->fetchAll();

        self::assertCount(1, $users);
        self::assertEquals('leszek.prabucki@gmail.com', $users[0]['email']);
        self::assertNotNull($user->id());
    }

    public function testThatSaveSameUserTwiceDoNotInsertNewRecord()
    {
        $user = new User(
            new Email('leszek.prabucki@gmail.com'),
            PasswordHash::fromPlainPassword('test123')
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