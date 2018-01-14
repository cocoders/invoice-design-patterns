<?php

declare(strict_types=1);

namespace spec\Invoice\Adapter\Doctrine\Domain;

use Invoice\Adapter\Doctrine\Domain\User;
use Invoice\Adapter\Doctrine\Domain\UserFactory as DoctrineUserFactory;
use Invoice\Domain\UserFactory;
use PhpSpec\ObjectBehavior;

/**
// * @mixin DoctrineUserFactory
 */
class UserFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineUserFactory::class);
        $this->shouldHaveType(UserFactory::class);
    }

    function it_creates_user()
    {
        $this->create('my@email.com', 'password')->shouldBeAnInstanceOf(User::class);
    }
}
