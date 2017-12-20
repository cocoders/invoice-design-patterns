<?php

declare(strict_types=1);

namespace spec\Invoice\Domain;

use Invoice\Domain\Email;
use Invoice\Domain\User;
use PhpSpec\ObjectBehavior;

/**
 * @mixin User
 */
class UserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new Email('leszek.prabucki@gmail.com'), password_hash('password', PASSWORD_BCRYPT));
        $this->shouldHaveType(User::class);
    }
}
