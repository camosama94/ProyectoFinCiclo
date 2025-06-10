<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610182924 extends AbstractMigration
{
    
    public function up(Schema $schema): void
    {
    $this->addSql('INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`) VALUES
    (6, "carlos94spain4@gmail.com", "[\"ROLE_USER\",\"ROLE_ADMIN_LIGA\",\"ROLE_STATS\"]", "$2y$13$0JDd2vQ1RSfBX7O1SKBKwOj5M4nadNP9wcXrE/mw5mHCnbXkm4ENS", "admin")');

    $this->addSql("INSERT INTO `competicion` (`id`, `admin_id`, `nombre`) VALUES
    (14, 6, 'Liga ACB')");

    $this->addSql("INSERT INTO `user_competicion` (`user_id`, `competicion_id`) VALUES
    (6, 14)");

    $this->addSql("INSERT INTO `equipo` (`id`, `competicion_id`, `nombre`, `entrenador`) VALUES
    (13, 14, 'Baskonia', 'qweqwe'),
    (14, 14, 'Valencia', 'sdfqef')");

    $this->addSql("INSERT INTO `jugador` (`id`, `id_equipo_id`, `nombre`, `posicion`, `altura`, `dorsal`) VALUES
    (1, 13, 'Juan Pérez', 'Base', 1.80, 4),
    (2, 13, 'Carlos López', 'Escolta', 1.85, 7),
    (3, 13, 'Miguel Torres', 'Alero', 1.92, 11),
    (4, 13, 'Andrés Martín', 'Ala-Pívot', 2.03, 13),
    (5, 13, 'Prueba', 'Base', 1.73, 8),
    (6, 13, 'Pedro García', 'Base', 1.78, 6),
    (7, 13, 'Luis Hernández', 'Escolta', 1.83, 9),
    (8, 13, 'Raúl Gómez', 'Alero', 1.90, 10),
    (9, 13, 'Javier Díaz', 'Ala-Pivot', 2.00, 12),
    (10, 13, 'Manuel Sánchez', 'Pivot', 2.05, 14),
    (11, 13, 'Sergio Ruiz', 'Pivot', 2.08, 15),
    (12, 14, 'Iván Romero', 'Escolta', 1.86, 8),
    (13, 14, 'Tomás Navarro', 'Alero', 1.91, 10),
    (14, 14, 'Adrián Vargas', 'Ala-Pívot', 2.01, 13),
    (15, 14, 'Hugo Molina', 'Pívot', 2.07, 15),
    (16, 14, 'Rubén Cruz', 'Base', 1.79, 3),
    (17, 14, 'Óscar León', 'Escolta', 1.84, 6),
    (18, 14, 'Marcos Soto', 'Alero', 1.88, 9),
    (19, 14, 'Antonio Rivas', 'Ala-Pívot', 2.02, 12),
    (20, 14, 'Daniel Ortega', 'Pívot', 2.06, 14),
    (21, 14, 'David Fernández', 'Base', 1.82, 5)");
    }

    public function down(Schema $schema): void
{
    $this->addSql('DELETE FROM jugador WHERE id BETWEEN 1 AND 21');
    $this->addSql('DELETE FROM equipo WHERE id IN (13, 14)');
    $this->addSql('DELETE FROM user_competicion WHERE user_id = 6 AND competicion_id = 14');
    $this->addSql('DELETE FROM competicion WHERE id = 14');
    $this->addSql('DELETE FROM `user` WHERE id = 6');
}

}
