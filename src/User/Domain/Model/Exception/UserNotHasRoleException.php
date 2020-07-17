<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Exception;

use Exception;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;
use Throwable;

final class UserNotHasRoleException extends Exception
{
    public function __construct(UserId $userId, UserRole $role, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('User identified by "%s" does not has the role "%s"', $userId, $role),
            $code,
            $previous
        );
    }
}
