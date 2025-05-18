<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250517150748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE peticion_rol ADD competicion_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peticion_rol ADD CONSTRAINT FK_8CEE4CE0D9407152 FOREIGN KEY (competicion_id) REFERENCES competicion (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8CEE4CE0D9407152 ON peticion_rol (competicion_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE peticion_rol DROP FOREIGN KEY FK_8CEE4CE0D9407152
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8CEE4CE0D9407152 ON peticion_rol
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peticion_rol DROP competicion_id
        SQL);
    }
}
