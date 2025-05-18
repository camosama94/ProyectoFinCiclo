<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506154849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE accion (id INT AUTO_INCREMENT NOT NULL, id_partido_id INT NOT NULL, id_jugador_id INT NOT NULL, tipo_de_accion VARCHAR(255) NOT NULL, minuto TIME NOT NULL, valor SMALLINT NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, INDEX IDX_8A02E3B4B40FEE63 (id_partido_id), INDEX IDX_8A02E3B41D2FCD94 (id_jugador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE competicion (id INT AUTO_INCREMENT NOT NULL, admin_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, INDEX IDX_78C44397642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE equipo (id INT AUTO_INCREMENT NOT NULL, competicion_id INT NOT NULL, nombre VARCHAR(200) NOT NULL, entrenador VARCHAR(100) NOT NULL, INDEX IDX_C49C530BD9407152 (competicion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jugador (id INT AUTO_INCREMENT NOT NULL, id_equipo_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, posicion VARCHAR(20) NOT NULL, altura NUMERIC(5, 2) DEFAULT NULL, dorsal INT NOT NULL, INDEX IDX_527D6F18820E47CA (id_equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE partido (id INT AUTO_INCREMENT NOT NULL, id_equipo_local_id INT NOT NULL, id_equipo_visitante_id INT NOT NULL, id_usuario_id INT NOT NULL, competicion_id INT NOT NULL, fecha DATETIME NOT NULL, localizacion VARCHAR(255) DEFAULT NULL, activo TINYINT(1) NOT NULL, INDEX IDX_4E79750B61298DFB (id_equipo_local_id), INDEX IDX_4E79750BCACEACDA (id_equipo_visitante_id), INDEX IDX_4E79750B7EB2C349 (id_usuario_id), INDEX IDX_4E79750BD9407152 (competicion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE peticion_rol (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, rol VARCHAR(50) NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_8CEE4CE0DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE stats_jugador (id INT AUTO_INCREMENT NOT NULL, id_partido_id INT NOT NULL, id_jugador_id INT NOT NULL, jugando TINYINT(1) NOT NULL, expulsado TINYINT(1) NOT NULL, puntos SMALLINT NOT NULL, tiros2_anot SMALLINT NOT NULL, tiros2_int SMALLINT NOT NULL, tiros3_anot SMALLINT NOT NULL, tiros3_int SMALLINT NOT NULL, tiros1_anot SMALLINT NOT NULL, tiros1_int SMALLINT NOT NULL, rebote_of SMALLINT NOT NULL, rebote_def SMALLINT NOT NULL, asistencias SMALLINT NOT NULL, tapones SMALLINT NOT NULL, robos SMALLINT NOT NULL, perdidas SMALLINT NOT NULL, faltas_com SMALLINT NOT NULL, faltas_rec SMALLINT NOT NULL, mas_menos SMALLINT NOT NULL, valoracion SMALLINT NOT NULL, minutos TIME NOT NULL, INDEX IDX_CBC72E45B40FEE63 (id_partido_id), INDEX IDX_CBC72E451D2FCD94 (id_jugador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE accion ADD CONSTRAINT FK_8A02E3B4B40FEE63 FOREIGN KEY (id_partido_id) REFERENCES partido (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE accion ADD CONSTRAINT FK_8A02E3B41D2FCD94 FOREIGN KEY (id_jugador_id) REFERENCES jugador (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE competicion ADD CONSTRAINT FK_78C44397642B8210 FOREIGN KEY (admin_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE equipo ADD CONSTRAINT FK_C49C530BD9407152 FOREIGN KEY (competicion_id) REFERENCES competicion (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jugador ADD CONSTRAINT FK_527D6F18820E47CA FOREIGN KEY (id_equipo_id) REFERENCES equipo (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partido ADD CONSTRAINT FK_4E79750B61298DFB FOREIGN KEY (id_equipo_local_id) REFERENCES equipo (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partido ADD CONSTRAINT FK_4E79750BCACEACDA FOREIGN KEY (id_equipo_visitante_id) REFERENCES equipo (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partido ADD CONSTRAINT FK_4E79750B7EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partido ADD CONSTRAINT FK_4E79750BD9407152 FOREIGN KEY (competicion_id) REFERENCES competicion (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peticion_rol ADD CONSTRAINT FK_8CEE4CE0DB38439E FOREIGN KEY (usuario_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stats_jugador ADD CONSTRAINT FK_CBC72E45B40FEE63 FOREIGN KEY (id_partido_id) REFERENCES partido (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stats_jugador ADD CONSTRAINT FK_CBC72E451D2FCD94 FOREIGN KEY (id_jugador_id) REFERENCES jugador (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE accion DROP FOREIGN KEY FK_8A02E3B4B40FEE63
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE accion DROP FOREIGN KEY FK_8A02E3B41D2FCD94
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE competicion DROP FOREIGN KEY FK_78C44397642B8210
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE equipo DROP FOREIGN KEY FK_C49C530BD9407152
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE jugador DROP FOREIGN KEY FK_527D6F18820E47CA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B61298DFB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partido DROP FOREIGN KEY FK_4E79750BCACEACDA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partido DROP FOREIGN KEY FK_4E79750B7EB2C349
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partido DROP FOREIGN KEY FK_4E79750BD9407152
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE peticion_rol DROP FOREIGN KEY FK_8CEE4CE0DB38439E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stats_jugador DROP FOREIGN KEY FK_CBC72E45B40FEE63
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stats_jugador DROP FOREIGN KEY FK_CBC72E451D2FCD94
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE accion
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE competicion
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE equipo
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jugador
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE partido
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE peticion_rol
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE stats_jugador
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
    }
}
