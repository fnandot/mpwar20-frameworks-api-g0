<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\User\Domain\Model\ValueObject;

use Ramsey\Uuid\Uuid;

final class UserId implements \Stringable
{
    private function __construct(private string $id)
    {
        $this->guardIdIsValid($id);
    }

    private function guardIdIsValid(string $id): void
    {
        if (!Uuid::isValid($id)) {
            throw new \RuntimeException($id);
        }
    }

    public static function generate(): self
    {
        return new self((string) Uuid::uuid4());
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
