<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class GetLogSummariesByEnvironment implements ApplicationService
{
    /** @var LogSummaryRepository */
    private $repository;

    public function __construct(LogSummaryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return LogSummary[]
     */
    public function __invoke(GetLogSummariesByEnvironmentRequest $request): LogSummaryCollectionResponse
    {
        $domainLogLevels = 0 === count($request->levels()) ?
            LogLevel::all() :
            $this->mapToDomainLevels(...$request->levels());

        $logSummaries = $this
            ->repository
            ->findByEnvironmentAndLevels($request->environment(), ...$domainLogLevels);

        return new LogSummaryCollectionResponse(
            ...$this->buildLogSummaryResponses(...$logSummaries)
        );
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

    /**
     * @return LogSummaryResponse[]
     */
    private function buildLogSummaryResponses(LogSummary ...$logSummaries): array
    {
        return array_map(
            static function (LogSummary $summary) {
                return new LogSummaryResponse(
                    (string) $summary->id(),
                    $summary->environment(),
                    (string) $summary->level(),
                    $summary->count()->toInt(),
                    $summary->updatedOn()
                );
            },
            $logSummaries
        );
    }
}
