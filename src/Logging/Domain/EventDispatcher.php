<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain;

use LaSalle\GroupZero\Logging\Domain\Model\Event\DomainEvent;

interface EventDispatcher
{
    public function dispatch(DomainEvent $event);
}
