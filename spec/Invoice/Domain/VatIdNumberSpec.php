<?php

namespace spec\Invoice\Domain;

use Invoice\Domain\Exception\VatIdNumberIsEmpty;
use Invoice\Domain\Exception\VatIdNumberIsNotValid;
use Invoice\Domain\VatIdNumber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin VatIdNumber $number
 */
class VatIdNumberSpec extends ObjectBehavior
{
    function it_is_initializable_with_polish_tax_number()
    {
        $this->beConstructedThrough('polish', ['9562307984']);
        $this->shouldHaveType(VatIdNumber::class);
        $this->__toString()->shouldBe('9562307984');
    }

    function it_is_initializable_with_polish_tax_number_when_number_is_empty()
    {
        $this->beConstructedThrough('polish', ['']);

        $this->shouldThrow(new VatIdNumberIsEmpty())->duringInstantiation();
    }

    function it_is_initializable_with_polish_tax_number_when_number_check_sum_is_not_valid()
    {
        $this->beConstructedThrough('polish', ['123456789']);

        $this->shouldThrow(VatIdNumberIsNotValid::class)->duringInstantiation();
    }

    function it_is_initalizable_empty()
    {
        $this->beConstructedThrough('empty');

        $this->shouldHaveType(VatIdNumber::class);
    }
}
