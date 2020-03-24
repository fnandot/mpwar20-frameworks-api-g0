<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Logging\Application\Exception\LogSummaryNotFoundException;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;

final class GetLogSummary implements ApplicationService
{
    /** @var LogSummaryRepository */
    private $repository;

    public function __construct(LogSummaryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetLogSummaryRequest $request): LogSummaryResponse
    {
        $summary = $this->repository->find($request->id());

        if (null === $summary) {
            throw new LogSummaryNotFoundException($request->id());
        }

        return new LogSummaryResponse(
            $summary->id(),
            $summary->environment(),
            (string) $summary->level(),
            $summary->count()
        );
    }
}
