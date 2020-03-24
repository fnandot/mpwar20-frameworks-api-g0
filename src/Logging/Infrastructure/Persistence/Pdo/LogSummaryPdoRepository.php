<?php

declare(strict_types = 1);

namespace LaSalle\GroupZero\Logging\Infrastructure\Persistence\Pdo;

use Closure;
use DateTimeImmutable;
use LaSalle\GroupZero\Logging\Domain\Model\Aggregate\LogSummary;
use LaSalle\GroupZero\Logging\Domain\Model\Repository\LogSummaryRepository;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogCount;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogLevel;
use LaSalle\GroupZero\Logging\Domain\Model\ValueObject\LogSummaryId;
use PDO;

final class LogSummaryPdoRepository implements LogSummaryRepository
{
    private const COLUMN_ID          = 'id';
    private const COLUMN_ENVIRONMENT = 'environment';
    private const COLUMN_LEVEL       = 'level';
    private const COLUMN_COUNT       = 'count';
    private const COLUMN_UPDATED_ON  = 'updated_on';

    private const DATABASE_MAPPING = [
        self::COLUMN_ID          => PDO::PARAM_STR,
        self::COLUMN_ENVIRONMENT => PDO::PARAM_STR,
        self::COLUMN_LEVEL       => PDO::PARAM_STR,
        self::COLUMN_COUNT       => PDO::PARAM_STR,
        self::COLUMN_UPDATED_ON  => PDO::PARAM_STR,
    ];

    private const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /** @var PDO */
    private $pdo;

    /** @var string */
    private $tableName;

    public function __construct(PDO $pdo, string $tableName)
    {
        $this->pdo       = $pdo;
        $this->tableName = $tableName;
    }

    public function find(LogSummaryId $id): ?LogSummary
    {
        $selectFromStatement = $this
            ->prepareSelectStatement(...array_keys(static::DATABASE_MAPPING));

        $statement = $this
            ->pdo
            ->prepare(
                <<<SQL
$selectFromStatement WHERE `id` = :id
SQL
            );

        $statement->bindValue(
            self::COLUMN_ID,
            (string) $id,
            static::DATABASE_MAPPING[self::COLUMN_ID]
        );

        $statement->execute();

        $found = $statement
            ->fetchAll(
                PDO::FETCH_FUNC,
                $this->mapToDomainFunction()
            );

        return reset($found) ?: null;
    }

    public function findByEnvironmentAndLevels(string $environment, LogLevel ...$levels): array
    {
        $selectFromStatement = $this
            ->prepareSelectStatement(...array_keys(static::DATABASE_MAPPING));

        $inStatement = $this->prepareMultipleParam(count($levels));

        $statement = $this
            ->pdo
            ->prepare(
                <<<SQL
$selectFromStatement WHERE `environment` = ? AND `level` IN ({$inStatement})
SQL
            );

        $levelValues = array_map($this->mapLogLevelToDatabaseFunction(), $levels);

        $statement->execute(array_merge([$environment], $levelValues));

        return $statement
            ->fetchAll(
                PDO::FETCH_FUNC,
                $this->mapToDomainFunction()
            );
    }

    public function findOneByEnvironmentAndLevel(string $environment, LogLevel $level): ?LogSummary
    {
        $selectFromStatement = $this
            ->prepareSelectStatement(...array_keys(static::DATABASE_MAPPING));

        $statement = $this
            ->pdo
            ->prepare(
                <<<SQL
$selectFromStatement WHERE `environment` = :environment AND `level` = :level
SQL
            );

        $statement->bindValue(
            self::COLUMN_ENVIRONMENT,
            $environment,
            static::DATABASE_MAPPING[self::COLUMN_ENVIRONMENT]
        );

        $statement->bindValue(self::COLUMN_LEVEL, (string) $level, static::DATABASE_MAPPING[self::COLUMN_LEVEL]);

        $statement->execute();

        $found = $statement
            ->fetchAll(
                PDO::FETCH_FUNC,
                $this->mapToDomainFunction()
            );

        return reset($found) ?: null;
    }

    public function save(LogSummary $logSummary): void
    {
        $data = $this->mapToDatabase($logSummary);

        $columnNames  = array_keys($data);
        $columnNumber = count($columnNames);

        $quotedColumnNames = array_map($this->quoteColumnNameFunction(), $columnNames);
        $columnsStatement  = implode(',', $quotedColumnNames);
        $valuesStatement   = $this->prepareMultipleParam($columnNumber);

        $onUpdateStatement = implode(
            ',',
            array_map(
                static function (string $name): string {
                    return $name.'=?';
                },
                $quotedColumnNames
            )
        );

        $statement = $this->pdo->prepare(
            <<<SQL
INSERT INTO {$this->tableName} ({$columnsStatement}) VALUES ({$valuesStatement})
ON DUPLICATE KEY UPDATE {$onUpdateStatement}
SQL
        );

        foreach ($columnNames as $index => $column) {
            $parameterNumber = $index + 1;
            $statement->bindValue(
                $parameterNumber,
                $data[$column],
                static::DATABASE_MAPPING[$column]
            );
            $statement->bindValue(
                $parameterNumber + $columnNumber,
                $data[$column],
                static::DATABASE_MAPPING[$column]
            );
        }

        $statement->execute();
    }

    private function mapToDatabase(LogSummary $logSummary): array
    {
        return [
            self::COLUMN_ID          => $logSummary->id(),
            self::COLUMN_ENVIRONMENT => $logSummary->environment(),
            self::COLUMN_LEVEL       => (string) $logSummary->level(),
            self::COLUMN_COUNT       => $logSummary->count()->toInt(),
            self::COLUMN_UPDATED_ON  => $logSummary->updatedOn()->format(static::DATETIME_FORMAT),
        ];
    }

    private function mapToDomainFunction(): Closure
    {
        $mapLogLevelToDomain = $this->mapLogLevelToDomainFunction();

        return static function (
            string $id,
            string $environment,
            string $level,
            int $count,
            string $updatedOn
        ) use ($mapLogLevelToDomain): LogSummary {
            return new LogSummary(
                LogSummaryId::fromString($id),
                $environment,
                $mapLogLevelToDomain($level),
                LogCount::initialized($count),
                DateTimeImmutable::createFromFormat(static::DATETIME_FORMAT, $updatedOn)
            );
        };
    }

    private function prepareSelectStatement(string ...$columnNames): string
    {
        $selectColumnsStatement = implode(
            ',',
            array_map(
                function (string $columnName): string {
                    return $this->tableName.'.'.$this->quoteColumnNameFunction()($columnName);
                },
                $columnNames
            )
        );

        return "SELECT {$selectColumnsStatement} FROM {$this->tableName}";
    }

    private function quoteColumnNameFunction(): Closure
    {
        return static function (string $name): string {
            return '`'.$name.'`';
        };
    }

    private function prepareMultipleParam(int $number): string
    {
        return implode(',', array_fill(0, $number, '?'));
    }

    private function mapLogLevelToDatabaseFunction(): callable
    {
        return 'strval';
    }

    private function mapLogLevelToDomainFunction(): callable
    {
        return static function (string $level): LogLevel {
            return LogLevel::fromString($level);
        };
    }
}
