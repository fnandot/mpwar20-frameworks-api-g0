<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Monolog;

use DateTimeImmutable;
use LaSalle\GroupZero\Logging\Application\CreateLogEntry;
use LaSalle\GroupZero\Logging\Application\CreateLogEntryRequest;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

final class CreateLogEntryHandler extends AbstractProcessingHandler
{
    public function __construct(
        private CreateLogEntry $createLogEntry,
        private string $kernelEnvironment,
        $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        ($this->createLogEntry)(
            new CreateLogEntryRequest(
                $record['extra']['id'],
                $this->kernelEnvironment,
                strtolower($record['level_name']),
                $record['message'],
                DateTimeImmutable::createFromMutable($record['datetime'])
            )
        );
    }
}
