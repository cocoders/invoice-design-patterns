<?php

namespace spec\Invoice\Application\UseCase;

use Invoice\Application\TransactionManager;
use Invoice\Application\UseCase\EditProfile;
use Invoice\Application\UseCase\EditProfile\Responder;
use Invoice\Domain\Email;
use Invoice\Domain\Profile;
use Invoice\Domain\ProfileFactory;
use Invoice\Domain\UserRepository;
use Invoice\Domain\User;
use Invoice\Domain\Exception\UserNotFound;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin EditProfile
 */
class EditProfileSpec extends ObjectBehavior
{
    function let(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        ProfileFactory $profileFactory
    ) {
        $this->beConstructedWith($transactionManager, $userRepository, $profileFactory);
    }

    function it_creates_user_and_store_in_repository(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        ProfileFactory $profileFactory,
        Profile $profile,
        User $user
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'))->willReturn($user);
        $profileFactory->create(Argument::cetera())->willReturn($profile);
        $user->changeProfile($profile)->shouldBeCalled();
        $transactionManager->commit()->shouldBeCalled();

        $this->execute(new EditProfile\Command(
            'leszek.prabucki@gmail.com',
            'Company name',
            '9562307984',
            'Królewskie Wzgórze 21/9, 80-283 Gdańsk'
        ));
    }

    function it_notifies_responder_when_user_is_edited_successfully(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        ProfileFactory $profileFactory,
        Profile $profile,
        Responder $responder,
        User $user
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'))->willReturn($user);
        $profileFactory->create(Argument::cetera())->willReturn($profile);
        $user->changeProfile($profile)->shouldBeCalled();
        $responder->userEditedSuccesfully($user)->shouldBeCalled();
        $transactionManager->commit()->shouldBeCalled();

        $this->registerResponder($responder);
        $this->execute(new EditProfile\Command(
            'leszek.prabucki@gmail.com',
            'Company name',
            '9562307984',
            'Królewskie Wzgórze 21/9, 80-283 Gdańsk'
        ));
    }

    function it_notifies_responder_when_user_is_not_found(
        TransactionManager $transactionManager,
        UserRepository $userRepository,
        ProfileFactory $profileFactory,
        Profile $profile,
        Responder $responder,
        User $user
    ) {
        $transactionManager->begin()->shouldBeCalled();
        $userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'))->willThrow(UserNotFound::class);
        $profileFactory->create(Argument::cetera())->willReturn($profile);
        $user->changeProfile($profile)->willReturn();
        $transactionManager->rollback()->shouldBeCalled();

        $responder->userNotFound(new Email('leszek.prabucki@gmail.com'))->shouldBeCalled();

        $this->registerResponder($responder);
        $this->execute(new EditProfile\Command(
            'leszek.prabucki@gmail.com',
            'Company name',
            '9562307984',
            'Królewskie Wzgórze 21/9, 80-283 Gdańsk'
        ));
    }
}
