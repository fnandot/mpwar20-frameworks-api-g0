<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;
use Ramsey\Uuid\Uuid;

final class UserAuthenticatedDomainEvent implements DomainEvent
{
    /** @var UserId */
    private $aggregateId;

    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        UserId $aggregateId,
        DateTimeImmutable $occurredOn
    ) {
        $this->aggregateId = $aggregateId;
        $this->occurredOn = $occurredOn;
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
