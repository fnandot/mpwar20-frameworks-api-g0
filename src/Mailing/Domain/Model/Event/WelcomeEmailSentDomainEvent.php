<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Mailing\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;

final class WelcomeEmailSentDomainEvent implements DomainEvent
{
    public function __construct(private UserId $aggregateId, private Email $email, private DateTimeImmutable $occurredOn)
    {
    }

    public function aggregateId(): UserId
    {
        return $this->aggregateId;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
