<?php

declare(strict_types=1);

namespace Invoice\Domain\Exception;

use InvalidArgumentException;

class EmailIsNotValid extends InvalidArgumentException
{
}