<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogEntryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\Pagination;

final class GetLogEntries
{
    /** @var LogEntryRepository */
    private $repository;

    public function __construct(LogEntryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetLogEntriesRequest $request)
    {
        return $this
            ->repository
            ->findByEnvironmentPaginated(
                $request->environment(),
                new Pagination(
                    $request->page(),
                    $request->elementsPerPage()
                )
            );
    }
}
