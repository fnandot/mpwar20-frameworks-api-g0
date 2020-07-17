<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\ValueObject;

final class Email
{
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
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
