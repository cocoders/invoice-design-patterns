<?php

namespace spec\Invoice\Domain;

use Invoice\Domain\Profile;
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
    function it_is_initializable(Email $email, PasswordHash $hash, Profile $profile)
    {
        $this->beConstructedWith(
            $email,
            $hash,
            $profile
        );

        $this->email()->shouldBe($email);
        $this->password()->shouldBe($hash);
        $this->profile()->shouldBe($profile);
    }
}
