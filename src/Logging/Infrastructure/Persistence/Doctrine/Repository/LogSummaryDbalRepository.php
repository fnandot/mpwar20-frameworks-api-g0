<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Repository;

use Closure;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Type\LogCountType;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Type\LogLevelType;
use LaSalle\GroupZero\Logging\Infrastructure\Persistence\Doctrine\Type\LogSummaryIdType;
use PDO;

final class LogSummaryDbalRepository implements LogSummaryRepository
{
    private const COLUMN_ID          = 'id';
    private const COLUMN_ENVIRONMENT = 'environment';
    private const COLUMN_LEVEL       = 'level';
    private const COLUMN_COUNT       = 'count';
    private const COLUMN_UPDATED_ON  = 'updated_on';

    private const DATABASE_MAPPING = [
        self::COLUMN_ID          => LogSummaryIdType::NAME,
        self::COLUMN_ENVIRONMENT => Types::STRING,
        self::COLUMN_LEVEL       => LogLevelType::NAME,
        self::COLUMN_COUNT       => LogCountType::NAME,
        self::COLUMN_UPDATED_ON  => Types::DATETIME_IMMUTABLE,
    ];

    /** @var Connection */
    private $connection;

    /** @var string */
    private $tableName;

    public function __construct(Connection $connection, string $tableName)
    {
        $this->connection = $connection;
        $this->tableName  = $tableName;
    }

    public function find(LogSummaryId $logSummaryId): ?LogSummary
    {
        $statement = $this
            ->createNamedQueryBuilder()
            ->where('ls.id = :id')
            ->setParameters(
                [
                    self::COLUMN_ID => $logSummaryId,
                ],
                static::DATABASE_MAPPING
            )
            ->setMaxResults(1)
            ->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === (bool) $data) {
            $statement->closeCursor();

            return null;
        }

        return $this->mapToDomainFunction()($data);
    }

    public function findByEnvironmentAndLevels(string $environment, LogLevel ...$levels): array
    {
        $statement = $this
            ->connection
            ->createQueryBuilder()
            ->select('ls.*')
            ->from('log_summary', 'ls')
            ->where('ls.environment = :environment')
            ->andWhere('ls.level IN (:levels)')
            ->setParameter('environment', $environment)
            /* LogLevel implementa __toString */
            ->setParameter('levels', $levels, Connection::PARAM_STR_ARRAY)
            ->setMaxResults(count($levels))
            ->execute();

        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $data) {
            $results[] = new LogSummary(
                $this->connection->convertToPHPValue($data['id'], LogSummaryIdType::NAME),
                $this->connection->convertToPHPValue($data['environment'], Types::STRING),
                $this->connection->convertToPHPValue($data['level'], LogLevelType::NAME),
                $this->connection->convertToPHPValue($data['count'], LogCountType::NAME),
                $this->connection->convertToPHPValue($data['updated.on'], Types::DATETIME_IMMUTABLE)
            );
        }

        return $results ?? [];
    }

    public function findOneByEnvironmentAndLevel(string $environment, LogLevel $level): ?LogSummary
    {
        $statement = $this
            ->createNamedQueryBuilder()
            ->where('ls.environment = :environment')
            ->andWhere('ls.level = :level')
            ->setParameters(
                [
                    self::COLUMN_ENVIRONMENT => $environment,
                    self::COLUMN_LEVEL       => $level,
                ],
                static::DATABASE_MAPPING
            )
            ->setMaxResults(1)
            ->execute();

        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === (bool) $data) {
            $statement->closeCursor();

            return null;
        }

        return $this->mapToDomainFunction()($data);
    }

    public function save(LogSummary $logSummary): void
    {
        $exists = $this->aggregateExists($logSummary);

        $data = [
            self::COLUMN_ENVIRONMENT => $logSummary->environment(),
            self::COLUMN_LEVEL       => $logSummary->level(),
            self::COLUMN_COUNT       => $logSummary->count(),
            self::COLUMN_UPDATED_ON  => $logSummary->updatedOn(),
        ];

        if (false === $exists) {
            $this->connection->insert(
                $this->tableName,
                ['id' => (string) $logSummary->id()] + $data,
                static::DATABASE_MAPPING
            );

            return;
        }

        $this->connection->update(
            $this->tableName,
            $data,
            [self::COLUMN_ID => $logSummary->id()],
            self::DATABASE_MAPPING
        );
    }

    private function aggregateExists(LogSummary $logSummary): bool
    {
        $statement = $this
            ->connection
            ->prepare('SELECT 1 FROM ' . $this->tableName . ' ls WHERE ls.id = :id LIMIT 1');

        $statement->execute(['id' => (string) $logSummary->id()]);

        return 1 === $statement->rowCount();
    }

    private function mapToDomainFunction(): Closure
    {
        return function (array $data): LogSummary {
            $hydratedValues = [];

            foreach ($data as $column => $value) {
                $hydratedValues[$column] = $this->hydrateFunction()($column, $value);
            }

            return new LogSummary(
                $hydratedValues[static::COLUMN_ID],
                $hydratedValues[static::COLUMN_ENVIRONMENT],
                $hydratedValues[static::COLUMN_LEVEL],
                $hydratedValues[static::COLUMN_COUNT],
                $hydratedValues[static::COLUMN_UPDATED_ON]
            );
        };
    }

    private function hydrateFunction(): Closure
    {
        return function ($param, $value) {
            return $this->connection->convertToPHPValue($value, static::DATABASE_MAPPING[$param]);
        };
    }

    private function createNamedQueryBuilder(string $alias = 'ls'): QueryBuilder
    {
        return $this
            ->connection
            ->createQueryBuilder()
            ->select(
                array_map(
                    static function (string $columnName) use ($alias): string {
                        return $alias . '.' . $columnName;
                    },
                    array_keys(static::DATABASE_MAPPING)
                )
            )
            ->from($this->tableName, $alias);
    }
}
