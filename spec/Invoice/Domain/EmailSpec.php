<?php

namespace spec\Invoice\Domain;

use Invoice\Domain\Email;
use Invoice\Domain\Exception\EmailIsEmpty;
use Invoice\Domain\Exception\EmailIsNotValid;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Email
 */
class EmailSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('leszek.prabucki@gmail.com');

        $this->__toString()->shouldBe('leszek.prabucki@gmail.com');
    }

    function it_throws_email_is_empty_exception_for_empty_mail()
    {
        $this->beConstructedWith('');

        $this->shouldThrow(EmailIsEmpty::class)->duringInstantiation();
    }

    function it_throws_email_is_not_valid_exception_for_not_valid_mail()
    {
        $this->beConstructedWith('not-valid');

        $this->shouldThrow(EmailIsNotValid::class)->duringInstantiation();
    }
}
