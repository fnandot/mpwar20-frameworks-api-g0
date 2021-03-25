<?php

declare(strict_types=1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class EnvironmentType extends StringType
{
    public const NAME = 'environment';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $field['length'] = 32;

        return $platform->getVarcharTypeDeclarationSQL($field);
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
