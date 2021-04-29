<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogEntryId;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class CreateLogEntry
{
    public function __construct(private LogEntryRepository $repository, private DomainEventBus $eventBus)
    {
    }

    public function __invoke(CreateLogEntryRequest $request): LogEntry
    {
        $logLevel = new LogLevel($request->level());

        $logEntry = LogEntry::create(
            LogEntryId::fromString($request->id()),
            $request->environment(),
            $logLevel,
            $request->message(),
            $request->occurredOn()
        );

        $this->repository->save($logEntry);
        $this->eventBus->publish(...$logEntry->pullDomainEvents());

        return $logEntry;
    }
}
