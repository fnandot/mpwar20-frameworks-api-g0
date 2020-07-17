<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\Email;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserId;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;
use Ramsey\Uuid\Uuid;

final class UserRegisteredDomainEvent implements DomainEvent
{
    /** @var UserId */
    private $aggregateId;

    /** @var Email */
    private $email;

    /** @var UserRole[] */
    private $roles;

    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        UserId $aggregateId,
        Email $email,
        array $roles,
        DateTimeImmutable $occurredOn
    ) {
        $this->aggregateId = $aggregateId;
        $this->email = $email;
        $this->roles = $roles;
        $this->occurredOn = $occurredOn;
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
