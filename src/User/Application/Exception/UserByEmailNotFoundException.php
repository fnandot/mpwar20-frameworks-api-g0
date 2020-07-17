<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Application\Exception;

use Exception;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use Throwable;

final class UserByEmailNotFoundException extends Exception
{
    public function __construct(Email $email, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('User with email "%s" were not found', $email),
            $code,
            $previous
        );
    }
}
