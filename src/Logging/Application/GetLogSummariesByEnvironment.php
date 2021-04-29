<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class GetLogSummariesByEnvironment implements ApplicationService
{
    public function __construct(private LogSummaryRepository $repository)
    {
    }

    /**
     * @return LogSummary[]
     */
    public function __invoke(GetLogSummariesByEnvironmentRequest $request): array
    {
        $domainLogLevels = 0 === count($request->levels()) ?
            LogLevel::all() :
            $this->mapToDomainLevels(...$request->levels());

        return $this
            ->repository
            ->findByEnvironmentAndLevels($request->environment(), ...$domainLogLevels);
    }

    /**
     * @return LogLevel[]
     */
    private function mapToDomainLevels(string ...$levels): array
    {
        $domainLogLevels = [];

        foreach ($levels as $level) {
            $domainLogLevels[] = LogLevel::fromString($level);
        }

        return $domainLogLevels;
    }
}
