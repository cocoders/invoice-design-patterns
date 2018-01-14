<?php

declare(strict_types=1);

namespace Tests\Invoice\Application\UseCase;

use Invoice\Adapter\Doctrine\Application\TransactionManager;
use Invoice\Adapter\Doctrine\Domain\User;
use Invoice\Adapter\Doctrine\Domain\UserFactory;
use Invoice\Adapter\Doctrine\Domain\Users;
use Invoice\Adapter\Doctrine\UnitOfWork;
use Invoice\Application\UseCase\RegisterUser;
use Tests\Invoice\DoctrineTestCase;

/**
 * @integration
 */
class RegisterUserTest extends DoctrineTestCase
{
    /**
     * @var RegisterUser
     */
    private $registerUser;

    public function setUp()
    {
        parent::setUp();
        $this->registerUser = new RegisterUser(
            new TransactionManager($this->em),
            new Users(new UserFactory(), $this->em, $this->em->getClassMetadata(User::class)),
            new UserFactory()
        );
    }

    function testThatRegisterUserUseCaseStoreItInDatabase()
    {
        $this->registerUser->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            password_hash('ktoIdziePoPiwo', PASSWORD_BCRYPT)
        ));

        $users = $this->pdo->query('SELECT * FROM users')->fetchAll();

        self::assertCount(1, $users);
        self::assertEquals('leszek.prabucki@gmail.com', $users[0]['email']);
    }

    function testThatCannotAddUserTwice()
    {
        $this->registerUser->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            password_hash('ktoIdziePoPiwo', PASSWORD_BCRYPT)
        ));
        $this->registerUser->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            password_hash('ktoIdziePoPiwo', PASSWORD_BCRYPT)
        ));

        $users = $this->pdo->query('SELECT * FROM users')->fetchAll();

        self::assertCount(1, $users);
        self::assertEquals('leszek.prabucki@gmail.com', $users[0]['email']);
    }
}
