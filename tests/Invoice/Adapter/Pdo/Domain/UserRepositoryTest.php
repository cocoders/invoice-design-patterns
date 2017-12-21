<?php

namespace Tests\Invoice\Adapter\Pdo\Domain;

use Invoice\Adapter\Pdo\Domain\UserFactory;
use Invoice\Adapter\Pdo\Domain\UserRepository;
use Invoice\Domain\DefaultProfileFactory;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\Profile;
use Invoice\Domain\User;
use Invoice\Domain\VatIdNumber;
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
        $this->userFactory = new UserFactory(new DefaultProfileFactory());
        $this->userRepository = new UserRepository(
            $this->getConnection(),
            $this->userFactory
        );

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

        $user = $this->userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'));
        $this->userRepository->add($user);

        $stmt = $this->getConnection()->query('SELECT * FROM users');
        $users = $stmt->fetchAll();

        self::assertCount(1, $users);
        self::assertEquals('leszek.prabucki@gmail.com', $users[0]['email']);
        self::assertNotNull($user->id());
    }

    public function testThatGetUserByEmail()
    {
        $user = $this->userFactory->create(
            'leszek.prabucki@gmail.com',
            'test123'
        );
        $this->userRepository->add($user);

        /**
         * @var \Invoice\Adapter\Pdo\Domain\User $user
         */
        $user = $this->userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'));
        self::assertInstanceOf(
            User::class,
            $user
        );
        self::assertNotNull($user->id());
    }

    public function testThatThrowsUserNotFoundExceptionWhenUserIsNotFound()
    {
        $user = $this->userFactory->create(
            'leszek.prabucki@gmail.com',
            'test123'
        );
        $this->userRepository->add($user);

        $this->expectException(UserNotFound::class);
        $this->userRepository->getByEmail(new Email('jan.kowalski@gmail.com'));
    }

    public function testThatUpdateUserInDatabase()
    {
        $user = $this->userFactory->create(
            'leszek.prabucki@gmail.com',
            'test123'
        );
        $this->userRepository->add($user);

        $user->changeProfile(new Profile(
            'test',
            VatIdNumber::empty(),
            'address'
        ));
        $this->userRepository->add($user);

        $user = $this->userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'));

        self::assertEquals('test', $user->profile()->name());
        self::assertEquals('address', $user->profile()->address());
    }

    public function testThatTaxIdNumberIsUpdated()
    {
        $user = $this->userFactory->create(
            'leszek.prabucki@gmail.com',
            'test123'
        );
        $this->userRepository->add($user);

        $user->changeProfile(new Profile(
            'test',
            VatIdNumber::polish('9562307984'),
            'address'
        ));
        $this->userRepository->add($user);

        $user = $this->userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'));

        self::assertEquals('9562307984', (string) $user->profile()->vatIdNumber());
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