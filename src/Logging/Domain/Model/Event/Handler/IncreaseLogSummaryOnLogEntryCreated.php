<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event\Handler;

use Exception;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Event\LogEntryCreatedDomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

final class IncreaseLogSummaryOnLogEntryCreated
{
    public function __construct(private LogSummaryRepository $repository, private DomainEventBus $eventBus)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(LogEntryCreatedDomainEvent $event): void
    {
        $environment = $event->environment();
        $level       = new LogLevel($event->level());

        $summary = $this
            ->repository
            ->findOneByEnvironmentAndLevel($environment, $level);

        if (null === $summary) {
            $summary = LogSummary::create(LogSummaryId::generate(), $environment, $level);
        }

        $summary->increase();

        $domainEvents = $summary->pullDomainEvents();

        $this->repository->save($summary);

        $this->eventBus->publish(...$domainEvents);
    }
}
