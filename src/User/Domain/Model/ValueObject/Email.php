<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\ValueObject;

final class Email implements \Stringable
{
    public function __construct(private string $email)
    {
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function equals(self $other): bool
    {
        return $this->email === $other->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
