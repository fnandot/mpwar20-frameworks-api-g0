<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Event\Handler;

use LaSalle\GroupZero\Logging\Domain\Model\Event\LogEntryCreatedDomainEvent;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

final class PublishToMercureOnLogEntryCreated
{
    /** @var PublisherInterface */
    private $publisher;

    /** @var string */
    private $mercureResourceEntrypoint;

    public function __construct(PublisherInterface $publisher, string $mercureResourceEntrypoint)
    {
        $this->publisher                 = $publisher;
        $this->mercureResourceEntrypoint = $mercureResourceEntrypoint;
    }

    public function __invoke(LogEntryCreatedDomainEvent $event): void
    {
        $update = new Update(
            sprintf('%s/log-entries/%s', $this->mercureResourceEntrypoint, $event->aggregateId()),
            json_encode(
                [
                    'id'          => $event->aggregateId(),
                    'type'        => 'log_entry_created',
                    'environment' => $event->environment(),
                    'level'       => $event->level(),
                    'message'     => $event->message(),
                    'occurred-on' => $event->occurredOn()->getTimestamp(),
                ]
            ),
            [
                sprintf('%s/roles/developer', $this->mercureResourceEntrypoint),
            ],
            (string) Uuid::uuid4()
        );

        ($this->publisher)($update);
    }
}
