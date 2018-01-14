<?php

namespace spec\Invoice\Application\UseCase;

use Invoice\Application\TransactionManager;
use Invoice\Application\UseCase\EditProfile;
use Invoice\Domain\Email;
use Invoice\Domain\Exception\UserNotFound;
use Invoice\Domain\User;
use Invoice\Domain\Users;
use Invoice\Domain\VatNumber;
use Invoice\Domain\VatNumberFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EditProfileSpec extends ObjectBehavior
{
    function let(TransactionManager $transactionManager, Users $users, VatNumberFactory $vatNumberFactory)
    {
        $this->beConstructedWith($transactionManager, $users, $vatNumberFactory);
    }

    function it_changes_user_profile(
        TransactionManager $transactionManager,
        Users $users,
        User $user,
        VatNumberFactory $vatNumberFactory
    ) {
        $vatNumber = VatNumber::fromString('234');
        $vatNumberFactory->create('234')->willREturn($vatNumber);
        $transactionManager->begin()->shouldBeCalled();
        $users->get(new Email('leszek.prabucki@gmail.com'))->willReturn($user);
        $user->changeProfile(new User\Profile(
            $vatNumber,
            'Leszek Prabucki',
            'address'
        ))->shouldBeCalled();

        $transactionManager->commit()->shouldBeCalled();

        $this->execute(new EditProfile\Command(
            'leszek.prabucki@gmail.com',
            '234',
            'Leszek Prabucki',
            'address'
        ));
    }

    function it_notify_responder_if_user_not_found(
        TransactionManager $transactionManager,
        Users $users,
        User $user,
        VatNumberFactory $vatNumberFactory,
        EditProfile\Responder $responder
    ) {
        $vatNumber = VatNumber::fromString('234');
        $vatNumberFactory->create('234')->willReturn($vatNumber);
        $transactionManager->begin()->shouldBeCalled();
        $users->get(new Email('leszek.prabucki@gmail.com'))->willThrow(new UserNotFound());
        $transactionManager->rollback()->shouldBeCalled();
        $user->changeProfile(Argument::any())->shouldNotBeCalled();

        $transactionManager->commit()->shouldNotBeCalled();

        $responder->userNotFound(new UserNotFound())->shouldBeCalled();
        $this->registerResponder($responder);
        $this->execute(new EditProfile\Command(
            'leszek.prabucki@gmail.com',
            '234',
            'Leszek Prabucki',
            'address'
        ));
    }

    function it_notify_responder_when_profile_edited(
        TransactionManager $transactionManager,
        Users $users,
        User $user,
        VatNumberFactory $vatNumberFactory,
        EditProfile\Responder $responder
    ) {
        $vatNumber = VatNumber::fromString('234');
        $vatNumberFactory->create('234')->willReturn($vatNumber);
        $transactionManager->begin()->shouldBeCalled();
        $users->get(new Email('leszek.prabucki@gmail.com'))->willReturn($user);
        $user->changeProfile(new User\Profile(
            $vatNumber,
            'Leszek Prabucki',
            'address'
        ))->shouldBeCalled();

        $transactionManager->commit()->shouldBeCalled();

        $responder->profileChanged(new Email('leszek.prabucki@gmail.com'), Argument::type(User\Profile::class))->shouldBeCalled();
        $this->registerResponder($responder);
        $this->execute(new EditProfile\Command(
            'leszek.prabucki@gmail.com',
            '234',
            'Leszek Prabucki',
            'address'
        ));
    }
}
