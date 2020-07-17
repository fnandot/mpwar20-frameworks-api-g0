<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Repository;

use LaSalle\GroupZero\User\Domain\Model\Aggregate\User;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;

interface UserRepository
{
    public function findOne(UserId $userId): ?User;

    public function findOneByEmail(Email $email): ?User;

    public function save(User $user): void;
}
