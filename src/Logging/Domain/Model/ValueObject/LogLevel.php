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
    private $value;

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public static function fromString(string $value): self
    {
        return new static($value);
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
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isGreaterOrEqualThan(LogLevel $other): bool
    {
        $currentIndex = array_search((string) $this, static::$allowedValues, true);
        $otherIndex   = array_search((string) $other, static::$allowedValues, true);

        return $currentIndex <= $otherIndex;
    }

    private function setValue(string $value): void
    {
        if (!in_array($value, static::$allowedValues, true)) {
            throw new InvalidLogLevelException($value);
        }

        $this->value = $value;
    }
}
