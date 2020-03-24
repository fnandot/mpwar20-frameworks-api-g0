<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Domain\Model\ValueObject;

use LaSalle\GroupZero\Logging\Domain\Model\Exception\InvalidLogEntryIdException;
use Ramsey\Uuid\Uuid;

final class LogEntryId
{
    /** @var string */
    private $id;

    public static function generate(): self
    {
        return new self((string) Uuid::uuid4());
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    private function __construct(string $id)
    {
        $this->guardIdIsValid($id);

        $this->id = $id;
    }

    private function guardIdIsValid(string $id): void
    {
        if (!Uuid::isValid($id)) {
            throw new InvalidLogEntryIdException($id);
        }
    }

    public function equals($other): bool
    {
        return $this->id === $other->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
