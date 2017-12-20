<?php

namespace spec\Invoice\Domain;

use Invoice\Domain\User;
use Invoice\Domain\Email;
use Invoice\Domain\PasswordHash;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin User
 */
class UserSpec extends ObjectBehavior
{
    function it_is_initializable(Email $email, PasswordHash $hash)
    {
        $this->beConstructedWith(
            $email,
            $hash
        );

        $this->email()->shouldBe($email);
        $this->password()->shouldBe($hash);
    }
}
