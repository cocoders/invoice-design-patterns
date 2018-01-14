<?php

namespace spec\Invoice\Domain;

use Invoice\Domain\Exception\VatNumberNotValid;
use Invoice\Domain\VatNumber;
use PhpSpec\ObjectBehavior;

/**
 * @mixin VatNumber
 */
class VatNumberSpec extends ObjectBehavior
{
    function it_can_be_created_from_any_string()
    {
        $this->beConstructedThrough('fromString', ['']);

        $this->shouldHaveType(VatNumber::class);
    }

    function it_can_be_created_from_valid_polish_number()
    {
        $this->beConstructedThrough('polish', ['956-230-79-84']);

        $this->shouldHaveType(VatNumber::class);
    }

    function it_cannot_be_created_from_invalid_polish_number()
    {
        $this->beConstructedThrough('polish', ['9562300000']);

        $this->shouldThrow(VatNumberNotValid::class)->duringInstantiation();
    }
}
