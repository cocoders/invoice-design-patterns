<?php

namespace Tests\Invoice\Domain;

use Invoice\Domain\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @dataProvider invalidEmails
     */
    public function testThatCannotBeCreatedFromInvalidEmail(string $email)
    {
        $this->expectException(\InvalidArgumentException::class);

        new Email($email);
    }

    public function invalidEmails(): array
    {
        return [
            ['invalid-email'],
            [''],
            ['leszek.prabucki@'],
            ['aaa@a']
        ];
    }
}