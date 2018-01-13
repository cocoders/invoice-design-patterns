<?php

namespace spec\Invoice\Adapter\Pdo\Domain;

use Invoice\Adapter\Pdo\Domain\User;
use Invoice\Adapter\Pdo\Domain\UserFactory as PdoUserFactory;
use Invoice\Domain\UserFactory;
use PhpSpec\ObjectBehavior;

/**
 * @mixin PdoUserFactory
 */
class UserFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PdoUserFactory::class);
        $this->shouldHaveType(UserFactory::class);
    }

    function it_creates_user()
    {
        $this->create('my@email.com', 'password')->shouldBeAnInstanceOf(User::class);
    }
}
