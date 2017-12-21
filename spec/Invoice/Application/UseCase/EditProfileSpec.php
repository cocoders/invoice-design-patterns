<?php

namespace spec\Invoice\Application\UseCase;

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
        UserRepository $userRepository,
        ProfileFactory $profileFactory
    ) {
        $this->beConstructedWith($userRepository, $profileFactory);
    }

    function it_creates_user_and_store_in_repository(
        UserRepository $userRepository,
        ProfileFactory $profileFactory,
        Profile $profile,
        User $user
    ) {
        $userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'))->willReturn($user);
        $profileFactory->create(Argument::cetera())->willReturn($profile);
        $user->changeProfile($profile)->shouldBeCalled();
        $userRepository->add($user)->shouldBeCalled();

        $this->execute(new EditProfile\Command(
            'leszek.prabucki@gmail.com',
            'Company name',
            '9562307984',
            'Królewskie Wzgórze 21/9, 80-283 Gdańsk'
        ));
    }

    function it_notifies_responder_when_user_is_edited_successfully(
        UserRepository $userRepository,
        ProfileFactory $profileFactory,
        Profile $profile,
        Responder $responder,
        User $user
    ) {
        $userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'))->willReturn($user);
        $profileFactory->create(Argument::cetera())->willReturn($profile);
        $user->changeProfile($profile)->shouldBeCalled();
        $userRepository->add($user)->shouldBeCalled();
        $responder->userEditedSuccesfully($user)->shouldBeCalled();

        $this->registerResponder($responder);
        $this->execute(new EditProfile\Command(
            'leszek.prabucki@gmail.com',
            'Company name',
            '9562307984',
            'Królewskie Wzgórze 21/9, 80-283 Gdańsk'
        ));
    }

    function it_notifies_responder_when_user_is_not_found(
        UserRepository $userRepository,
        ProfileFactory $profileFactory,
        Profile $profile,
        Responder $responder,
        User $user
    ) {
        $userRepository->getByEmail(new Email('leszek.prabucki@gmail.com'))->willThrow(UserNotFound::class);
        $profileFactory->create(Argument::cetera())->willReturn($profile);
        $user->changeProfile($profile)->willReturn();
        $userRepository->add($user)->shouldNotBeCalled();

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
