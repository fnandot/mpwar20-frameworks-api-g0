<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;

final class UserRoleAddedDomainEvent implements DomainEvent
{
    public function __construct(
        private UserId $aggregateId,
        private UserRole $role,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public function aggregateId(): UserId
    {
        return $this->aggregateId;
    }

    public function role(): UserRole
    {
        return $this->role;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
