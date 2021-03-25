<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\ValueObject;

final class UserRole implements \Stringable
{
    public const USER      = 'user';
    public const DEVELOPER = 'developer';

    public static array $allowed = [
        self::USER,
        self::DEVELOPER,
    ];

    public function __construct(private string $role)
    {
        $this->guardIsAllowed($role);
    }

    private function guardIsAllowed(string $role): void
    {
        if (!in_array($role, self::$allowed, true)) {
            throw new \RuntimeException(
                sprintf(
                    'Given value "%s" is no allowed. Allowed are "%s".',
                    $role,
                    implode(',', self::$allowed)
                )
            );
        }
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function developer(): self
    {
        return new self(self::DEVELOPER);
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
