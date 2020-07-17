<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Domain\Model\ValueObject;

use LaSalle\GroupZero\User\Domain\Model\Exception\InvalidPasswordException;

final class Password
{
    /** @var string */
    private $password;

    public function __construct(string $password)
    {
        $this->guardPasswordIsValid($password);
        $this->password = $password;
    }

    public function toString(): string
    {
        return $this->password;
    }

    public function equals(self $other): bool
    {
        return $this->password === $other->password;
    }

    public function __toString(): string
    {
        return $this->password;
    }

    private function guardPasswordIsValid(string $value): void
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $value)) {
            throw new InvalidPasswordException();
        }
    }
}
