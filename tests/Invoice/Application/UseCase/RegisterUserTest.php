<?php

declare(strict_types=1);

namespace Tests\Invoice\Application\UseCase;

use Invoice\Adapter\Pdo\Application\TransactionManager;
use Invoice\Adapter\Pdo\Domain\UserFactory;
use Invoice\Adapter\Pdo\Domain\Users;
use Invoice\Adapter\Pdo\UnitOfWork;
use Invoice\Application\UseCase\RegisterUser;
use Tests\Invoice\DbTestCase;

/**
 * @integration
 */
class RegisterUserTest extends DbTestCase
{
    /**
     * @var RegisterUser
     */
    private $registerUser;

    public function setUp()
    {
        parent::setUp();
        $unitOfWork = new UnitOfWork();
        $this->registerUser = new RegisterUser(
            new TransactionManager($this->pdo, $unitOfWork),
            new Users($this->pdo, $unitOfWork),
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
