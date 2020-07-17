<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\User\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use LaSalle\GroupZero\User\Domain\Model\ValueObject\UserRole;

final class UserRoleCollectionType extends JsonType
{
    public const NAME = 'user_role_collection';

    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        $rawCollection = parent::convertToPHPValue($value, $platform);

        return array_map(
            static function (string $role): UserRole {
                return new UserRole($role);
            },
            $rawCollection
        );
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        $rawCollection = array_map('strval', array_values($value));

        return parent::convertToDatabaseValue($rawCollection, $platform);
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
