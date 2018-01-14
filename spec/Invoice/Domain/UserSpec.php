<?php

declare(strict_types=1);

namespace spec\Invoice\Domain;

use Invoice\Domain\Email;
use Invoice\Domain\Exception\ProfileNotFound;
use Invoice\Domain\User;
use Invoice\Domain\VatNumber;
use PhpSpec\ObjectBehavior;

/**
 * @mixin User
 */
class UserSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(new Email('leszek.prabucki@gmail.com'), password_hash('password', PASSWORD_BCRYPT));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    function it_allows_to_change_profile()
    {
        $this->hasProfile()->shouldBe(false);
        $this->changeProfile(
            new User\Profile(
                VatNumber::polish('956-230-79-84'),
                'Leszek Prabucki',
                '80-283 Gdańsk, Królewskie Wzgórze'
            )
        );

        $this->hasProfile()->shouldBe(true);
        $this->profile()->shouldBeLike(
            new User\Profile(
                VatNumber::polish('956-230-79-84'),
                'Leszek Prabucki',
                '80-283 Gdańsk, Królewskie Wzgórze'
            )
        );
    }

    function it_throws_exception_when_why_try_to_access_profile_which_was_not_set()
    {
        $this->shouldThrow(ProfileNotFound::class)->duringProfile();
    }
}
