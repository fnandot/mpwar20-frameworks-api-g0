<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\Event\Handler;

use Exception;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEvent;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Event\LogEntryCreatedDomainEvent;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class IncreaseLogSummaryOnLogEntryCreated
{
    /** @var LogSummaryRepository */
    private $repository;

    /** @var DomainEventBus */
    private $eventBus;

    public function __construct(LogSummaryRepository $repository, DomainEventBus $eventBus)
    {
        $this->repository = $repository;
        $this->eventBus   = $eventBus;
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
            $summary = LogSummary::create($environment, $level);
        }

        $summary->increase();

        $domainEvents = $summary->pullDomainEvents();

        $this->repository->save($summary);

        $this->publishDomainEvents(...$domainEvents);
    }

    private function publishDomainEvents(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->eventBus->publish($domainEvent);
        }
    }
}
