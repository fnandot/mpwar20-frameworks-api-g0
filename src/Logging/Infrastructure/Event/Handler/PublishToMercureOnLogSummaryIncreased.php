<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Event\Handler;

use LaSalle\GroupZero\Logging\Domain\Model\Event\LogSummaryIncreasedDomainEvent;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

final class PublishToMercureOnLogSummaryIncreased
{
    public function __construct(private PublisherInterface $publisher, private string $mercureResourceEntrypoint)
    {
    }

    public function __invoke(LogSummaryIncreasedDomainEvent $event): void
    {
        $update = new Update(
            sprintf('%s/log-summaries/%s', $this->mercureResourceEntrypoint, $event->aggregateId()),
            json_encode(
                [
                    'id'           => (string)$event->aggregateId(),
                    'type'         => 'log_summary_increased',
                    'increased_by' => $event->increasedBy(),
                    'occurred_on'  => $event->occurredOn()->getTimestamp(),
                ]
            ),
            [
                sprintf('%s/roles/user', $this->mercureResourceEntrypoint),
            ],
            (string)Uuid::uuid4()
        );

        ($this->publisher)($update);
    }
}
