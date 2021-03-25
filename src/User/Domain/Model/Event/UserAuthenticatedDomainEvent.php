<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;

final class UserAuthenticatedDomainEvent implements DomainEvent
{
    public function __construct(private UserId $aggregateId, private DateTimeImmutable $occurredOn)
    {
    }

    public function aggregateId(): UserId
    {
        return $this->aggregateId;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
