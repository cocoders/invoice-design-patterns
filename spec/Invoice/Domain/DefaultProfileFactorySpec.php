<?php

namespace spec\Invoice\Domain;

use Invoice\Domain\DefaultProfileFactory;
use Invoice\Domain\Exception\VatIdNumberIsNotValid;
use Invoice\Domain\Profile;
use PhpSpec\ObjectBehavior;

/**
 * @mixin DefaultProfileFactory
 */
class DefaultProfileFactorySpec extends ObjectBehavior
{
    function it_creates_profile_with_empty_data()
    {
        $profile = $this->create('', '', '');
        $profile->shouldHaveType(Profile::class);
    }

    function it_does_not_allow_to_create_profile_with_invalid_polish_tax_number()
    {
        $this
            ->shouldThrow(VatIdNumberIsNotValid::class)
            ->duringCreate('', '12345', '')
        ;
    }
}
