<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Mailing\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;

final class WelcomeEmailSentDomainEvent implements DomainEvent
{
    /** @var UserId */
    private $aggregateId;

    /** @var Email */
    private $email;

    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(UserId $aggregateId, Email $email, DateTimeImmutable $occurredOn)
    {
        $this->aggregateId = $aggregateId;
        $this->email        = $email;
        $this->occurredOn  = $occurredOn;
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
