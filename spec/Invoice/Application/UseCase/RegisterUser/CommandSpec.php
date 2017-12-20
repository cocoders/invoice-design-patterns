<?php

namespace spec\Invoice\Application\UseCase\RegisterUser;

use Invoice\Application\UseCase\RegisterUser\Command;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(
            'leszek.prabucki@gmail.com',
            'password'
        );

        $this->email()->shouldBe('leszek.prabucki@gmail.com');
        $this->password()->shouldBe('password');
    }
}
