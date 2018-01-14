<?php

namespace spec\Invoice\Application\UseCase;

use Exception;
use InvalidArgumentException;
use Invoice\Application\UseCase\RegisterUser;
use Invoice\Application\TransactionManager;
use Invoice\Domain\Users;
use Invoice\Domain\UserFactory;
use Invoice\Domain\User;
use PhpSpec\ObjectBehavior;

/**
 * @mixin RegisterUser
 */
class RegisterUserSpec extends ObjectBehavior
{
    function let(
        TransactionManager $transactionManager,
        Users $users,
        UserFactory $userFactory
    ) {
        $this->beConstructedWith($transactionManager, $users, $userFactory);
    }

    function it_creates_and_store_user_in_repository(
        TransactionManager $transactionManager,
        Users $users,
        UserFactory $userFactory,
        User $user
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userFactory->create(
            'leszek.prabucki@gmail.com',
            'password'
        )->willReturn($user);
        $users->has($user)->willReturn(false);
        $users->add($user)->shouldBeCalled();
        $transactionManager->commit()->shouldBeCalled();

        $this->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_does_not_allows_to_create_user_if_user_which_such_email_already_exists_in_storage(
        TransactionManager $transactionManager,
        UserFactory $userFactory,
        User $user,
        Users $users
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userFactory->create(
            'leszek.prabucki@gmail.com',
            'password'
        )->willReturn($user);
        $users->has($user)->willReturn(true);
        $users->add($user)->shouldNotBeCalled();
        $transactionManager->commit()->shouldNotBeCalled();
        $transactionManager->rollback()->shouldBeCalled();

        $this->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_rollback_transaction_when_exception_will_be_thrown_in_user_factory(
        TransactionManager $transactionManager,
        UserFactory $userFactory
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userFactory->create(
            'leszek.prabucki@gmail.com',
            'password'
        )->willThrow(new InvalidArgumentException());
        $transactionManager->commit()->shouldNotBeCalled();
        $transactionManager->rollback()->shouldBeCalled();

        $this->shouldThrow(InvalidArgumentException::class)->duringExecute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_rollback_transaction_when_exception_will_be_thrown_in_users_repository(
        TransactionManager $transactionManager,
        Users $users,
        User $user,
        UserFactory $userFactory
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userFactory->create(
            'leszek.prabucki@gmail.com',
            'password'
        )->willReturn($user);
        $users->has($user)->willReturn(false);
        $users->add($user)->willThrow(new InvalidArgumentException());
        $transactionManager->commit()->shouldNotBeCalled();
        $transactionManager->rollback()->shouldBeCalled();

        $this->shouldThrow(InvalidArgumentException::class)->duringExecute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_rollback_transaction_when_exception_will_be_thrown_during_commit(
        TransactionManager $transactionManager,
        Users $users,
        User $user,
        UserFactory $userFactory
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userFactory->create(
            'leszek.prabucki@gmail.com',
            'password'
        )->willReturn($user);
        $users->has($user)->willReturn(false);
        $users->add($user)->shouldBeCalled();
        $transactionManager->commit()->willThrow(new Exception());
        $transactionManager->rollback()->shouldBeCalled();

        $this->shouldThrow(Exception::class)->duringExecute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_notify_responder_if_user_which_such_email_already_exists(
        TransactionManager $transactionManager,
        UserFactory $userFactory,
        User $user,
        Users $users,
        RegisterUser\Responder $responder
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userFactory->create(
            'leszek.prabucki@gmail.com',
            'password'
        )->willReturn($user);
        $users->has($user)->willReturn(true);
        $transactionManager->rollback()->shouldBeCalled();
        $responder->userAlreadyExists($user)->shouldBeCalled();

        $this->registerResponder($responder);
        $this->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_notify_responder_if_username_is_empty(
        RegisterUser\Responder $responder
    ) {
        $responder->emailIsEmpty()->shouldBeCalled();

        $this->registerResponder($responder);
        $this->execute(new RegisterUser\Command(
            '',
            'password'
        ));
    }
}
