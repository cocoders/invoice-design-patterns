<?php

declare(strict_types=1);

namespace Tests\Invoice\Domain;

use Invoice\Domain\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @dataProvider invalidEmails
     */
    public function testThatCannotBeCreatedWithInvalidEmail(string $invalidEmail)
    {
        $this->expectException(\LogicException::class);

        new Email($invalidEmail);
    }

    public function invalidEmails()
    {
        return [
            ['invalid@co'],
            ['invalid-a'],
            [''],
            ['aaaa@test']
        ];
    }
}