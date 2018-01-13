<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo;

use Invoice\Adapter\Pdo\Domain\User;

class UnitOfWork
{
    private $objects = [];
    private $objectComparatorMethods = [
        User::class => 'email'
    ];

    public function attach(object $object): void
    {
        if (isset($this->objects[spl_object_hash($object)])) {
            return;
        }

        $this->objects[spl_object_hash($object)] = $object;
    }

    public function detach(object $object): void
    {
        if (isset($this->objects[spl_object_hash($object)])) {
            unset($this->objects[spl_object_hash($object)]);
        }
    }

    public function has(object $object): bool
    {
        if (!isset($this->objectComparatorMethods[get_class($object)])) {
            return false;
        }

        $comparatorMethod = $this->objectComparatorMethods[get_class($object)];

        $givenTypeObjects = array_filter($this->objects, function (object $localObject) use ($object) {
            return get_class($localObject) === get_class($object);
        });

        return (bool) array_filter($givenTypeObjects, function (object $localObject) use ($object, $comparatorMethod) {
            return ((string) call_user_func([$object, $comparatorMethod])) === ((string) call_user_func([$localObject, $comparatorMethod]));
        });
    }

    public function clear(): void
    {
        $this->objects = [];
    }

    public function objects(): array
    {
        return $this->objects;
    }
}
