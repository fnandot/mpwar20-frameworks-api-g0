<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\ValueObject;

use LaSalle\GroupZero\Logging\Domain\Model\Exception\NegativeLogCountException;
use LaSalle\GroupZero\Logging\Domain\Model\Exception\NegativeLogCountIncreaseException;

final class LogCount
{
    /** @var int */
    private $count;

    private function __construct(int $count)
    {
        $this->guardIsPositive($count);
        $this->count = $count;
    }

    public static function zero(): self
    {
        return new static(0);
    }

    public static function initialized(int $count): self
    {
        return new static($count);
    }

    private function guardIsPositive(int $count): void
    {
        if ($this->isIntegerPositive($count)) {
            throw new NegativeLogCountException();
        }
    }

    private function isIntegerPositive(int $count): bool
    {
        return 0 > $count;
    }

    public function equals(LogCount $other): bool
    {
        return $this->count === $other->count;
    }

    public function toInt(): int
    {
        return $this->count;
    }

    public function increaseBy(int $increaseBy = 1): self
    {
        if ($this->isIntegerPositive($increaseBy)) {
            throw new NegativeLogCountIncreaseException();
        }

        return new static($this->count + $increaseBy);
    }
}
