<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;

final class UserRegisteredDomainEvent implements DomainEvent
{
    public function __construct(
        private UserId $aggregateId,
        private Email $email,
        /* @var UserRole[] */
        private array $roles,
        private DateTimeImmutable $occurredOn
    ) {
    }

    public function aggregateId(): UserId
    {
        return $this->aggregateId;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
