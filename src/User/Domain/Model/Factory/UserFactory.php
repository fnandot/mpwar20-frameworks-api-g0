<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Factory;

use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Password;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;

interface UserFactory
{
    public function register(Email $email, Password $password, UserRole ...$roles): User;
}
