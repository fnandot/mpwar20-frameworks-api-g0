<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model;

use ArrayIterator;
use IteratorAggregate;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogEntry;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\Pagination;

final class PaginatedLogEntryCollection implements IteratorAggregate
{
    /** @var Pagination */
    private $page;

    /** @var int */
    private $elementsPerPage;

    /** @var int */
    private $totalElements;

    /** @var LogEntry[] */
    private $elements;

    public static function empty(): self
    {
        return new static(0, 0, 0);
    }

    public function __construct(
        int $page,
        int $elementsPerPage,
        int $totalElements,
        LogEntry ...$elements
    ) {
        $this->page            = $page;
        $this->elementsPerPage = $elementsPerPage;
        $this->totalElements   = $totalElements;
        $this->elements        = $elements;
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
