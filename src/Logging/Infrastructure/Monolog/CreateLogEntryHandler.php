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
    /** @var CreateLogEntry */
    private $createLogEntry;

    /** @var string */
    private $kernelEnvironment;

    public function __construct(
        CreateLogEntry $createLogEntry,
        string $kernelEnvironment,
        $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);

        $this->createLogEntry    = $createLogEntry;
        $this->kernelEnvironment = $kernelEnvironment;
    }

    protected function write(array $record): void
    {
        ($this->createLogEntry)(
            new CreateLogEntryRequest(
                $record['extra']['id'],
                $this->kernelEnvironment,
                (string) strtolower($record['level_name']),
                $record['message'],
                DateTimeImmutable::createFromMutable($record['datetime'])
            )
        );
    }
}
