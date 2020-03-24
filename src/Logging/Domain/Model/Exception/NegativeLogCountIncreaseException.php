<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Exception;

use Exception;
use Throwable;

final class NegativeLogCountIncreaseException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('You cannot increase the log count with a negative number!', $code, $previous);
    }
}
