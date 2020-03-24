<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogCount;

final class LogCountType extends IntegerType
{
    public const NAME = 'log_count';

    public function convertToPHPValue($value, AbstractPlatform $platform): LogCount
    {
        return LogCount::initialized((int) $value);
    }

    /**
     * @param LogCount $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        return $value->toInt();
    }

    public function getName(): string
    {
        return static::NAME;
    }
}
