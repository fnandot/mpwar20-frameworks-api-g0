<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200222185811 extends AbstractMigration
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
            <<<SQL
INSERT INTO dblogs.`user`
(id, roles, email, password)
VALUES(:id, :roles, :email, :password);
SQL
            ,
            [
                'id' => 'f4142f08-9e3f-446f-82a8-8c7bd79aa9fc',
                'roles' => '["user","developer"]',
                'email' => 'fernando.pradas@salle.url.edu',
                'password' => '$argon2i$v=19$m=1024,t=3,p=1$NVl5Tk5WSFN2dGxaa1h5aA$fKUSD0ybO4jzhZwFw7TGoW5vBpvIYgSSzEiPjmieH2E',
            ]
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DELETE FROM dblogs.`user` WHERE id=:id', ['id' => 'f4142f08-9e3f-446f-82a8-8c7bd79aa9fc']);
    }
}
