<?php

namespace Tests\Invoice\Adapter\Legacy\Application\RegisterUser;

use Invoice\Adapter\Legacy\Application\UseCase\RegisterUser\Errors;
use Invoice\Domain\Email;
use PHPUnit\Framework\TestCase;
use Invoice\Adapter\Legacy\Application\UseCase\RegisterUser\Responder;

class ResponderTest extends TestCase
{
    public function testThatAddEmailIsEmptyMessageToArray()
    {
        $array = new Errors();
        $responder = new Responder($array);
        $responder->emailIsEmpty();

        self::assertEquals('Email field was empty.', $array['email']);
    }

    public function testThatAddEmailIsNotValidMessageToArray()
    {
        $array = new Errors();
        $responder = new Responder($array);
        $responder->emailIsNotValid();

        self::assertEquals('Email is not valid.', $array['email']);
    }

    public function testThatAddPasswordIsNotValidMessageToArray()
    {
        $array = new Errors();
        $responder = new Responder($array);
        $responder->passwordIsNotValid();

        self::assertEquals('Password field was empty.', $array['password']);
    }

    public function testThatAddUserWithSameEmailExistsMessageToArray()
    {
        $array = new Errors();
        $responder = new Responder($array);
        $responder->userWithSameEmailAlreadyExists(new Email('leszek.prabucki@gmail.com'));

        self::assertEquals('User with given email exists already.', $array['email']);
    }
}