<?php

declare(strict_types=1);

namespace spec\Invoice\Domain;

use InvalidArgumentException;
use Invoice\Domain\Email;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Email
 */
class EmailSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('leszek.prabucki@gmail.com');
        $this->shouldHaveType(Email::class);
        $this->__toString()->shouldBe('leszek.prabucki@gmail.com');
    }

    function it_throws_exception_when_initialized_with_invalid_email()
    {
        $this->beConstructedWith('invalid');

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
