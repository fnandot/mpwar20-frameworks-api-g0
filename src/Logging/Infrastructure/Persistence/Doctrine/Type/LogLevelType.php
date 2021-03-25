<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;

final class LogLevelType extends Type
{
    public const NAME = 'log_level';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $singleQuoteValue = function (string $value): string {
            return "'".$value."'";
        };

        return 'ENUM('.implode(',', array_map($singleQuoteValue, LogLevel::$allowedValues)).')';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): LogLevel
    {
        return LogLevel::fromString($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
