<?php

declare(strict_types=1);

namespace Invoice\Domain\Exception;

use InvalidArgumentException;

final class UserNotFound extends InvalidArgumentException
{
}
