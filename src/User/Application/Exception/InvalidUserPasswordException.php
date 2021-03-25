<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application\Exception;

use Exception;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use Throwable;

final class InvalidUserPasswordException extends Exception
{
    public function __construct(UserId $userId, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Provided password for user "%s" is not valid', $userId),
            $code,
            $previous
        );
    }
}
