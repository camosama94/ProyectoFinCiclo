<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250517082620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_competicion (user_id INT NOT NULL, competicion_id INT NOT NULL, INDEX IDX_D1DF8A02A76ED395 (user_id), INDEX IDX_D1DF8A02D9407152 (competicion_id), PRIMARY KEY(user_id, competicion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_competicion ADD CONSTRAINT FK_D1DF8A02A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_competicion ADD CONSTRAINT FK_D1DF8A02D9407152 FOREIGN KEY (competicion_id) REFERENCES competicion (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user_competicion DROP FOREIGN KEY FK_D1DF8A02A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_competicion DROP FOREIGN KEY FK_D1DF8A02D9407152
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_competicion
        SQL);
    }
}
