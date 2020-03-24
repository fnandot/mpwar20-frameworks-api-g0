<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Exception;

use Exception;
use Throwable;

final class InvalidLogSummaryIdException extends Exception
{
    public function __construct(string $id, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Provided log summary id "%s" is invalid', $id), $code, $previous);
    }
}
