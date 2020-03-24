<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application\Exception;

use Exception;

final class LogSummaryNotFoundException extends Exception
{
    public function __construct(string $id, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('LogSummary with id "%s" was not found', $id), $code, $previous);
    }
}
