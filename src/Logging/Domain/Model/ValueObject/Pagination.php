<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\ValueObject;

use RuntimeException;

final class Pagination
{
    /** @var int $page */
    private $page;

    /** @var int $page */
    private $elementsPerPage;

    public function __construct(int $page, int $elementsPerPage)
    {
        $this->guardIsPositive($page);
        $this->guardIsPositive($elementsPerPage);

        $this->page            = $page;
        $this->elementsPerPage = $elementsPerPage;
    }

    private function guardIsPositive(int $value): void
    {
        if (0 > $value) {
            throw new RuntimeException('Given value should be positive');
        }
    }

    public function page(): int
    {
        return $this->page;
    }

    public function elementsPerPage(): int
    {
        return $this->elementsPerPage;
    }

    public function equals(self $other): bool
    {
        return $this->page === $other->page
            && $this->elementsPerPage === $other->elementsPerPage;
    }
}
