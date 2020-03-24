<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;
use Ramsey\Uuid\Uuid;

final class LogSummaryIncreasedDomainEvent implements DomainEvent
{
    /** @var LogSummaryId */
    private $id;

    /** @var string */
    private $aggregateId;

    /** @var int */
    private $increasedBy;

    /** @var DateTimeImmutable */
    private $occurredOn;

    public function __construct(
        LogSummaryId $id,
        int $count,
        DateTimeImmutable $occurredOn
    ) {
        $this->id          = (string) Uuid::uuid4();
        $this->aggregateId = $id;
        $this->increasedBy = $count;
        $this->occurredOn  = $occurredOn;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function aggregateId(): LogSummaryId
    {
        return $this->aggregateId;
    }

    public function increasedBy(): int
    {
        return $this->increasedBy;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
