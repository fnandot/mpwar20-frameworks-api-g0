<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\ValueObject;

use LaSalle\GroupZero\Logging\Domain\Model\Exception\InvalidLogLevelException;
use Stringable;

final class LogLevel implements Stringable
{
    public const EMERGENCY = 'emergency';
    public const ALERT     = 'alert';
    public const CRITICAL  = 'critical';
    public const ERROR     = 'error';
    public const WARNING   = 'warning';
    public const NOTICE    = 'notice';
    public const INFO      = 'info';
    public const DEBUG     = 'debug';

    public static array $allowedValues = [
        self::EMERGENCY,
        self::ALERT,
        self::CRITICAL,
        self::ERROR,
        self::WARNING,
        self::NOTICE,
        self::INFO,
        self::DEBUG,
    ];

    public function __construct(private string $value)
    {
        if (!in_array($value, LogLevel::$allowedValues, true)) {
            throw new InvalidLogLevelException($value);
        }
    }


    public static function fromString(string $value): self
    {
        return new LogLevel($value);
    }

    /**
     * @return LogLevel[]
     */
    public static function all(): array
    {
        return array_map([LogLevel::class, 'fromString'], LogLevel::$allowedValues);
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
        $currentIndex = array_search((string)$this, LogLevel::$allowedValues, true);
        $otherIndex   = array_search((string)$other, LogLevel::$allowedValues, true);

        return $currentIndex <= $otherIndex;
    }
}
