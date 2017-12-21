<?php

declare(strict_types=1);

namespace Invoice\Domain\Exception;

use InvalidArgumentException;
use Throwable;

class EmailIsEmpty extends InvalidArgumentException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: 'Email is empty', $code, $previous);
    }
}