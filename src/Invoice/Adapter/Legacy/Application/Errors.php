<?php

declare(strict_types=1);

namespace Invoice\Adapter\Legacy\Application;

use ArrayAccess;

class Errors implements ArrayAccess
{
    private $errors = [];

    public function addError(string $field, string $error): void
    {
        $this->errors[$field] = $error;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->errors[$offset]);
    }

    public function offsetGet($offset): string
    {
        return $this->errors[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->addError($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        unset($this->errors[$offset]);
    }
}
