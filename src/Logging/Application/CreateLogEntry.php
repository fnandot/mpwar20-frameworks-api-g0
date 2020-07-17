<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class CreateLogEntry
{
    /** @var LogEntryRepository */
    private $repository;

    /** @var DomainEventBus */
    private $eventBus;

    public function __construct(LogEntryRepository $repository, DomainEventBus $eventBus)
    {
        $this->repository = $repository;
        $this->eventBus   = $eventBus;
    }

    public function __invoke(CreateLogEntryRequest $request): LogEntryResponse
    {
        $logLevel = new LogLevel($request->level());

        $logEntry = LogEntry::create(
            $request->id(),
            $request->environment(),
            $logLevel,
            $request->message(),
            $request->occurredOn()
        );

        $this->repository->save($logEntry);
        $this->eventBus->publish(...$logEntry->pullDomainEvents());

        return new LogEntryResponse(
            $logEntry->id(),
            $logEntry->environment(),
            (string) $logEntry->level(),
            $logEntry->message(),
            $logEntry->occurredOn()
        );
    }
}
