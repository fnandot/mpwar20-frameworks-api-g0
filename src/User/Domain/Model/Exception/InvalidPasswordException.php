<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Exception;

use Exception;
use Throwable;

final class InvalidPasswordException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(
            'Your password should be at least 8 characters and contain at least one uppercase letter and a number.',
            $code,
            $previous
        );
    }
}
