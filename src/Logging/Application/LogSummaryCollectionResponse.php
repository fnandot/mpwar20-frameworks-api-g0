<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Application;

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class LogSummaryCollectionResponse implements IteratorAggregate, Countable
{
    /** @var LogSummaryResponse[] */
    private $summaries;

    public function __construct(LogSummaryResponse ...$summaries)
    {
        $this->summaries = $summaries;
    }

    public function summaries(): array
    {
        return $this->summaries;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->summaries);
    }

    public function count()
    {
        return count($this->summaries);
    }
}
