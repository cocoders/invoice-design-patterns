<?php

namespace spec\Invoice\Domain;

use InvalidArgumentException;
use Invoice\Domain\Email;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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

    function it_throws_invalid_argument_exception_for_empty_mail()
    {
        $this->beConstructedWith('');

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_throws_invalid_argument_exception_for_not_valid_mail()
    {
        $this->beConstructedWith('not-valid');

        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
