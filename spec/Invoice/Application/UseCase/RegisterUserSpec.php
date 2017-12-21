<?php

namespace spec\Invoice\Application\UseCase;

use Invoice\Application\UseCase\RegisterUser;
use Invoice\Application\UseCase\RegisterUser\Responder;
use Invoice\Domain\Email;
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
        UserRepository $userRepository,
        UserFactory $userFactory
    ) {
        $this->beConstructedWith($userRepository, $userFactory);
    }

    function it_creates_user_and_store_in_repository(
        UserRepository $userRepository,
        UserFactory $userFactory,
        User $user
    ) {
        $userFactory->create('leszek.prabucki@gmail.com', 'password')->willReturn(
            $user
        );

        $userRepository->add($user)->shouldBeCalled();

        $this->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }

    function it_notify_responder_when_user_is_registered(
        UserRepository $userRepository,
        UserFactory $userFactory,
        User $user,
        Responder $responder
    ) {
        $userFactory
            ->create('leszek.prabucki@gmail.com', 'password')
            ->willReturn(
                $user
            )
        ;

        $userRepository->add($user);
        $responder->userWasRegistered($user)->shouldBeCalled();

        $this->registerResponder($responder);
        $this->execute(new RegisterUser\Command(
            'leszek.prabucki@gmail.com',
            'password'
        ));
    }
}
