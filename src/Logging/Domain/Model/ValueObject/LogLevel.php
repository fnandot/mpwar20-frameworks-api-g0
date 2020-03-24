<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\ValueObject;

use LaSalle\GroupZero\Logging\Domain\Model\Exception\InvalidLogLevelException;

final class LogLevel
{
    public const EMERGENCY = 'emergency';
    public const ALERT     = 'alert';
    public const CRITICAL  = 'critical';
    public const ERROR     = 'error';
    public const WARNING   = 'warning';
    public const NOTICE    = 'notice';
    public const INFO      = 'info';
    public const DEBUG     = 'debug';

    public static $allowedValues = [
        self::EMERGENCY,
        self::ALERT,
        self::CRITICAL,
        self::ERROR,
        self::WARNING,
        self::NOTICE,
        self::INFO,
        self::DEBUG,
    ];

    /** @var string */
    private $level;

    public function __construct(string $level)
    {
        $this->setLevel($level);
    }

    public static function fromString(string $level): self
    {
        return new static($level);
    }

    /**
     * @return LogLevel[]
     */
    public static function all(): array
    {
        return array_map([static::class, 'fromString'], static::$allowedValues);
    }

    public function equals(self $other): bool
    {
        return $this->level === $other->level;
    }

    public function __toString(): string
    {
        return $this->level;
    }

    public function isGreaterOrEqualThan(LogLevel $other): bool
    {
        $currentIndex = array_search((string) $this, static::$allowedValues, true);
        $otherIndex   = array_search((string) $other, static::$allowedValues, true);

        return $currentIndex <= $otherIndex;
    }

    private function setLevel(string $level): void
    {
        if (!in_array($level, static::$allowedValues, true)) {
            throw new InvalidLogLevelException($level);
        }

        $this->level = $level;
    }
}
