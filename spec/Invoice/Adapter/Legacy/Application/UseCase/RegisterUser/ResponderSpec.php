<?php

namespace spec\Invoice\Adapter\Legacy\Application\UseCase\RegisterUser;

use Invoice\Adapter\Legacy\Application\Errors;
use Invoice\Adapter\Legacy\Application\UseCase\RegisterUser\Responder;
use Invoice\Domain\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin Responder
 */
class ResponderSpec extends ObjectBehavior
{
    function it_add_error_when_user_already_exists(User $user, Errors $errors)
    {
        $this->beConstructedWith($errors);
        $this->userAlreadyExists($user);

        $errors->addError('email', 'User with given email exists already.')->shouldHaveBeenCalled();
    }

    function it_do_nothing_on_successfull_register(User $user, Errors $errors)
    {
        $this->beConstructedWith($errors);
        $this->userRegistered($user);

        $errors->addError(Argument::cetera())->shouldNotHaveBeenCalled();
    }
}
