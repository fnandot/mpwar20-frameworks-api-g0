<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\Exception\InvalidLogLevelException;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogEntryId;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class CreateLogEntry
{
    /** @var LogEntryRepository */
    private $logEntryRepository;

    /** @var DomainEventBus */
    private $eventBus;

    public function __construct(LogEntryRepository $logEntryRepository, DomainEventBus $eventBus)
    {
        $this->logEntryRepository = $logEntryRepository;
        $this->eventBus           = $eventBus;
    }

    /**
     * @throws InvalidLogLevelException
     */
    public function __invoke(CreateLogEntryRequest $request): LogEntryResponse
    {
        $logLevel = new LogLevel($request->level());

        $logEntry = LogEntry::create(
            LogEntryId::fromString($request->id()),
            $request->environment(),
            $logLevel,
            $request->message(),
            $request->occurredOn()
        );

        $this->logEntryRepository->save($logEntry);
        $this->eventBus->publish(...$logEntry->pullDomainEvents());

        return new LogEntryResponse(
            (string) $logEntry->id(),
            $logEntry->environment(),
            (string) $logEntry->level(),
            $logEntry->message(),
            $logEntry->occurredOn()
        );
    }
}
