<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model;

use ArrayIterator;
use IteratorAggregate;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;

final class PaginatedLogEntryCollection implements IteratorAggregate
{
    /** @var LogEntry[] */
    private $elements;

    public function __construct(
        private int $page,
        private int $elementsPerPage,
        private int $totalElements,
        LogEntry ...$elements
    ) {
        $this->elements = $elements;
    }

    public static function empty(): self
    {
        return new static(0, 0, 0);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }

    public function page(): int
    {
        return $this->page;
    }

    public function elementsPerPage(): int
    {
        return $this->elementsPerPage;
    }

    public function totalElements(): int
    {
        return $this->totalElements;
    }

    public function elements(): array
    {
        return $this->elements;
    }
}
