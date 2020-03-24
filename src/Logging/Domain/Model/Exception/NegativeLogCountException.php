<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Exception;

use Exception;
use Throwable;

final class NegativeLogCountException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('A log count could not be negative!', $code, $previous);
    }
}
