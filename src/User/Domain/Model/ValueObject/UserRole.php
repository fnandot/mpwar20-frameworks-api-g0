<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\ValueObject;

final class UserRole
{
    public const USER      = 'user';
    public const DEVELOPER = 'developer';

    public static $allowed = [
        self::USER,
        self::DEVELOPER,
    ];

    private $role;

    public static function user(): self
    {
        return new static(static::USER);
    }

    public static function developer(): self
    {
        return new static(static::DEVELOPER);
    }

    public function __construct(string $role)
    {
        $this->guardIsAllowed($role);
        $this->role = $role;
    }

    private function guardIsAllowed(string $role): void
    {
        if (!in_array($role, static::$allowed, true)) {
            throw new \RuntimeException(sprintf('Given value "%s" is no allowed. Allowed are "%s".', $role, implode(',', static::$allowed)));
        }
    }

    public function toString(): string
    {
        return $this->role;
    }

    public function equals(self $other): bool
    {
        return $this->role === $other->role;
    }

    public function __toString(): string
    {
        return $this->role;
    }
}
