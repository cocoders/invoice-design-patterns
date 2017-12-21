<?php

namespace Invoice\Application;

interface TransactionManager
{
    public function begin();
    public function commit();
    public function rollback();
}
