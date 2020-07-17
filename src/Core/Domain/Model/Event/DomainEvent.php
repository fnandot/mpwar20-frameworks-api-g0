<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Core\Domain\Model\Event;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredOn(): DateTimeImmutable;
}
