<?php

namespace tests\Invoice\Adapter\Application;

use Invoice\Application\UnitOfWork;
use Invoice\Domain\Email;
use PHPUnit\Framework\TestCase;

class UnitOfWorkTest extends TestCase
{
    public function testThatAddSameObjectOnce()
    {
        $object = new Email('leszek.prabucki@gmail.com');

        $uow = new UnitOfWork();
        $uow->scheduleForInsert($object);
        $uow->scheduleForInsert($object);

        self::assertCount(
            1,
            $uow->objectsForInsert()
        );
    }

    public function testThatClearAllObjects()
    {
        $object = new Email('leszek.prabucki@gmail.com');

        $uow = new UnitOfWork();
        $uow->scheduleForInsert($object);
        $uow->scheduleForInsert($object);
        $uow->scheduleForUpdate(new Email('jan.ko@gmail.com'));
        $uow->clear();

        self::assertCount(
            0,
            $uow->objectsForInsert()
        );

        self::assertCount(
            0,
            $uow->objectsForUpdate()
        );
    }

    public function testIsScheduledForInsert()
    {
        $object = new Email('leszek.prabucki@gmail.com');

        $uow = new UnitOfWork();
        self::assertFalse($uow->isScheduled($object));
        $uow->scheduleForInsert($object);

        self::assertTrue($uow->isScheduled($object));
    }

    public function testIsScheduledForUpdate()
    {
        $object = new Email('leszek.prabucki@gmail.com');

        $uow = new UnitOfWork();
        self::assertFalse($uow->isScheduled($object));
        $uow->scheduleForUpdate($object);

        self::assertTrue($uow->isScheduled($object));
    }
}