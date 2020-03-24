<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Logging\Application\Exception\LogSummaryNotFoundException;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;

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
        $id = LogSummaryId::fromString($request->id());
        $summary = $this->repository->find($id);

        if (null === $summary) {
            throw new LogSummaryNotFoundException($request->id());
        }

        return new LogSummaryResponse(
            (string) $summary->id(),
            $summary->environment(),
            (string) $summary->level(),
            $summary->count()->toInt(),
            $summary->updatedOn()
        );
    }
}
