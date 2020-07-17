<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Service;

use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Password;

interface UserPasswordValidationService
{
    public function isValid(User $user, Password $password): bool;
}
