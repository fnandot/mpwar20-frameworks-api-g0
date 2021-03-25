<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Mailing\Domain\Model\Event\Handler;

use DateTimeImmutable;
use LaSalle\GroupZero\Core\Domain\Model\Event\DomainEventBus;
use LaSalle\GroupZero\Mailing\Domain\Model\Event\WelcomeEmailSentDomainEvent;
use LaSalle\GroupZero\Mailing\Domain\Model\Service\SendWelcomeEmailService;
use LaSalle\GroupZero\User\Domain\Model\Event\UserRegisteredDomainEvent;

final class SendWelcomeEmailOnUserRegistered
{
    public function __construct(private SendWelcomeEmailService $sendWelcomeEmailService, private DomainEventBus $domainEventBus)
    {
    }

    public function __invoke(UserRegisteredDomainEvent $event): void
    {
        // ($this->sendWelcomeEmailService)($event->email());

        $this->domainEventBus->publish(
            new WelcomeEmailSentDomainEvent(
                $event->aggregateId(),
                $event->email(),
                new DateTimeImmutable()
            )
        );
    }
}
