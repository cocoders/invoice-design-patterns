<?php

declare(strict_types=1);

namespace Invoice\Adapter\Pdo;

class UnitOfWork
{
    private $objects = [];

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

    public function clear(): void
    {
        $this->objects = [];
    }

    public function objects(): array
    {
        return $this->objects;
    }
}