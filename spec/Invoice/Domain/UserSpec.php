<?php

namespace spec\Invoice\Domain;

use Invoice\Domain\User;
use Invoice\Domain\Email;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin User
 */
class UserSpec extends ObjectBehavior
{
    function it_is_initializable(Email $email)
    {
        $hash = password_hash('password', PASSWORD_BCRYPT);
        $this->beConstructedWith(
            $email,
            $hash
        ); // $user = new User('leszek.prabucki@gmail.com', 'password');

        $this->email()->shouldBe($email);
        // self::assertEquals('leszek.prabucki@gmail.com', $user->email());
        $this->password()->shouldBe($hash);
        // self::assertEquals($hash, $user->password());
    }
}
