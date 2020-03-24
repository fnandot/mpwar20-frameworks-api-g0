<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version201912121717222223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE log_summary (id CHAR(36) NOT NULL COMMENT \'(DC2Type:log_summary_id)\', level ENUM(\'emergency\',\'alert\',\'critical\',\'error\',\'warning\',\'notice\',\'info\',\'debug\') NOT NULL COMMENT \'(DC2Type:log_level)\', environment VARCHAR(32) NOT NULL, updated_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', count INT UNSIGNED NOT NULL, UNIQUE INDEX environmentLevel (environment, level), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE log_summary');
    }
}
