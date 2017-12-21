<?php

namespace Invoice\Application;

class UnitOfWork
{
    private $insertPool;
    private $updatePool;

    public function __construct()
    {
        $this->insertPool = new \SplObjectStorage();
        $this->updatePool = new \SplObjectStorage();
    }

    public function scheduleForInsert(object $object): void
    {
        $this->insertPool->attach($object);
    }

    public function isScheduled(object $object): bool
    {
        return $this->insertPool->contains($object) || $this->updatePool->contains($object);
    }

    public function scheduleForUpdate(object $object): void
    {
        $this->updatePool->attach($object);
    }

    public function clear()
    {
        $this->insertPool = new \SplObjectStorage();
        $this->updatePool = new \SplObjectStorage();
    }

    public function objectsForInsert(): \SplObjectStorage
    {
        return $this->insertPool;
    }

    public function objectsForUpdate(): \SplObjectStorage
    {
        return $this->updatePool;
    }
}