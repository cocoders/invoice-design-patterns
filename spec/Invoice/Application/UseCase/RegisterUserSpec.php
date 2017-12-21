<?php

namespace spec\Invoice\Application\UseCase;

use Invoice\Application\UseCase\RegisterUser;
use Invoice\Application\UseCase\RegisterUser\Responder;
use Invoice\Application\TransactionManager;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\EmailIsEmpty;
use Invoice\Domain\Exception\EmailIsNotValid;
use Invoice\Domain\Exception\PasswordIsNotValid;
use Invoice\Domain\UserRepository;
use Invoice\Domain\User;
use Invoice\Domain\UserFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin RegisterUser
 */
class RegisterUserSpec extends ObjectBehavior
{
    function let(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        UserFactory $userFactory
    ) {
        $this->beConstructedWith(
            $transactionManager,
            $userRepository,
            $userFactory
        );
    }

    function it_creates_user_and_store_in_repository(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        UserFactory $userFactory,
        User $user
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userFactory->create('leszek.prabucki@gmail.com', 'password')->willReturn(
            $user
        );
        $userRepository->has($user)->willReturn(false);
        $userRepository->add($user)->shouldBeCalled();
        $transactionManager->commit()->shouldBeCalled();

        $this->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_notifies_responder_when_user_is_registered(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        UserFactory $userFactory,
        User $user,
        Responder $responder
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userFactory
            ->create('leszek.prabucki@gmail.com', 'password')
            ->willReturn(
                $user
            )
        ;
        $userRepository->has($user)->willReturn(false);
        $userRepository->add($user)->shouldBeCalled();
        $responder->userWasRegistered($user)->shouldBeCalled();
        $transactionManager->commit()->shouldBeCalled();

        $this->registerResponder($responder);
        $this->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_notifies_responder_when_user_which_given_email_is_found(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        UserFactory $userFactory,
        User $user,
        Responder $responder
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userFactory
            ->create('leszek.prabucki@gmail.com', 'password')
            ->willReturn(
                $user
            )
        ;
        $userRepository->has($user)->willReturn(true);
        $responder->userWithSameEmailAlreadyExists(
            new Email('leszek.prabucki@gmail.com')
        )->shouldBeCalled();
        $userRepository->add($user)->shouldNotBeCalled();
        $transactionManager->rollback()->shouldBeCalled();
        $transactionManager->commit()->shouldNotBeCalled();

        $this->registerResponder($responder);
        $this->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_notifies_responder_when_email_is_empty(
        UserFactory $userFactory,
        Responder $responder
    ) {
        $userFactory
            ->create(Argument::cetera())
            ->willThrow(
                new EmailIsEmpty()
            )
        ;

        $responder->emailIsEmpty()->shouldBeCalled();
        $this->registerResponder($responder);
        $this->execute(new RegisterUser\Command(
            '',
            'password'
        ));
    }

    function it_notifies_responder_when_email_is_not_valid(
        UserFactory $userFactory,
        Responder $responder
    ) {
        $userFactory
            ->create(Argument::cetera())
            ->willThrow(
                new EmailIsNotValid()
            )
        ;

        $responder->emailIsNotValid()->shouldBeCalled();
        $this->registerResponder($responder);
        $this->execute(new RegisterUser\Command(
            'invalid',
            'password'
        ));
    }

    function it_notifies_responder_when_password_is_not_valid(
        UserFactory $userFactory,
        Responder $responder
    ) {
        $userFactory
            ->create(Argument::cetera())
            ->willThrow(
                new PasswordIsNotValid()
            )
        ;

        $responder->passwordIsNotValid()->shouldBeCalled();
        $this->registerResponder($responder);
        $this->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            ''
        ));
    }
}
