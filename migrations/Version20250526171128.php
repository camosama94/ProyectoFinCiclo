<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250526171128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS before_insert_accion
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TRIGGER before_insert_accion
            BEFORE INSERT ON accion
            FOR EACH ROW
            BEGIN
                DECLARE next_num INT;
            
                -- Obtener el siguiente número de acción dentro del mismo partido
                SELECT COALESCE(
                    MAX(id % 1000), 0
                ) + 1
                INTO next_num
                FROM accion
                WHERE id_partido_id = NEW.id_partido_id;
            
                -- Asignar el nuevo id como partidoID * 1000 + número secuencial
                SET NEW.id = NEW.id_partido_id * 1000 + next_num;
            END
        SQL);

        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS after_insert_accion
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TRIGGER `after_insert_accion` AFTER INSERT ON `accion` 
            FOR EACH ROW
            BEGIN
                -- Actualizar estadísticas de tiros anotados y fallados
                IF NEW.tipo_de_accion = 'TA' THEN
                    -- Actualizar puntos y tiros según el valor del tiro
                    IF NEW.valor = 2 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos + 2,
                            tiros2_anot = tiros2_anot + 1,
                            tiros2_int = tiros2_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    ELSEIF NEW.valor = 3 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos + 3,
                            tiros3_anot = tiros3_anot + 1,
                            tiros3_int = tiros3_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    ELSEIF NEW.valor = 1 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos + 1,
                            tiros1_anot = tiros1_anot + 1,
                            tiros1_int = tiros1_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    END IF;
                ELSEIF NEW.tipo_de_accion = 'TF' THEN
                    -- Actualizar tiros intentados según el valor del tiro fallado
                    IF NEW.valor = 2 THEN
                        UPDATE stats_jugador 
                        SET tiros2_int = tiros2_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    ELSEIF NEW.valor = 3 THEN
                        UPDATE stats_jugador 
                        SET tiros3_int = tiros3_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    ELSEIF NEW.valor = 1 THEN
                        UPDATE stats_jugador 
                        SET tiros1_int = tiros1_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    END IF;
                END IF;
            
                -- Actualizar rebotes
                IF NEW.tipo_de_accion = 'RO' THEN
                    UPDATE stats_jugador 
                    SET rebote_of = rebote_of + 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                ELSEIF NEW.tipo_de_accion = 'RD' THEN
                    UPDATE stats_jugador 
                    SET rebote_def = rebote_def + 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                END IF;
            
                -- Actualizar asistencias
                IF NEW.tipo_de_accion = 'AS' THEN
                    UPDATE stats_jugador 
                    SET asistencias = asistencias + 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                END IF;
            
                -- Actualizar tapones
                IF NEW.tipo_de_accion = 'BL' THEN
                    UPDATE stats_jugador 
                    SET tapones = tapones + 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                END IF;
            
                -- Actualizar pérdidas
                IF NEW.tipo_de_accion = 'TO' THEN
                    UPDATE stats_jugador 
                    SET perdidas = perdidas + 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                END IF;
            
                -- Actualizar robos
                IF NEW.tipo_de_accion = 'ST' THEN
                    UPDATE stats_jugador 
                    SET robos = robos + 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                END IF;
            
                -- Actualizar faltas
                IF NEW.tipo_de_accion = 'FC' THEN
                    UPDATE stats_jugador 
                    SET faltas_com = faltas_com + 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
            
                    -- Expulsar al jugador si alcanza 5 faltas
                    IF (SELECT faltas_com FROM stats_jugador 
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id) = 5 THEN
                        UPDATE stats_jugador 
                        SET expulsado = 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    END IF;
                ELSEIF NEW.tipo_de_accion = 'FR' THEN
                    UPDATE stats_jugador 
                    SET faltas_rec = faltas_rec + 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                END IF;
            
                -- Actualizar expulsión directa
                IF NEW.tipo_de_accion = 'EX' THEN
                    UPDATE stats_jugador 
                    SET expulsado = 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                END IF;
            END
        SQL);

        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS before_delete_accion
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TRIGGER `before_delete_accion` BEFORE DELETE ON `accion` 
            FOR EACH ROW
            BEGIN
                -- Revertir estadísticas de tiros anotados y fallados
                IF OLD.tipo_de_accion = 'TA' THEN
                    IF OLD.valor = 2 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos - 2,
                            tiros2_anot = tiros2_anot - 1,
                            tiros2_int = tiros2_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    ELSEIF OLD.valor = 3 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos - 3,
                            tiros3_anot = tiros3_anot - 1,
                            tiros3_int = tiros3_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    ELSEIF OLD.valor = 1 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos - 1,
                            tiros1_anot = tiros1_anot - 1,
                            tiros1_int = tiros1_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    END IF;
                ELSEIF OLD.tipo_de_accion = 'TF' THEN
                    IF OLD.valor = 2 THEN
                        UPDATE stats_jugador 
                        SET tiros2_int = tiros2_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    ELSEIF OLD.valor = 3 THEN
                        UPDATE stats_jugador 
                        SET tiros3_int = tiros3_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    ELSEIF OLD.valor = 1 THEN
                        UPDATE stats_jugador 
                        SET tiros1_int = tiros1_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    END IF;
                END IF;
            
                -- Revertir rebotes
                IF OLD.tipo_de_accion = 'RO' THEN
                    UPDATE stats_jugador 
                    SET rebote_of = rebote_of - 1
                    WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                ELSEIF OLD.tipo_de_accion = 'RD' THEN
                    UPDATE stats_jugador 
                    SET rebote_def = rebote_def - 1
                    WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                END IF;
            
                -- Revertir asistencias
                IF OLD.tipo_de_accion = 'AS' THEN
                    UPDATE stats_jugador 
                    SET asistencias = asistencias - 1
                    WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                END IF;
            
                -- Revertir tapones
                IF OLD.tipo_de_accion = 'BL' THEN
                    UPDATE stats_jugador 
                    SET tapones = tapones - 1
                    WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                END IF;
            
                -- Revertir pérdidas
                IF OLD.tipo_de_accion = 'TO' THEN
                    UPDATE stats_jugador 
                    SET perdidas = perdidas - 1
                    WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                END IF;
            
                -- Revertir robos
                IF OLD.tipo_de_accion = 'ST' THEN
                    UPDATE stats_jugador 
                    SET robos = robos - 1
                    WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                END IF;
            
                -- Revertir faltas
                IF OLD.tipo_de_accion = 'FC' THEN
                    UPDATE stats_jugador 
                    SET faltas_com = faltas_com - 1
                    WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                ELSEIF OLD.tipo_de_accion = 'FR' THEN
                    UPDATE stats_jugador 
                    SET faltas_rec = faltas_rec - 1
                    WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                END IF;
            
                -- Revertir expulsión directa
                IF OLD.tipo_de_accion = 'EX' THEN
                    UPDATE stats_jugador 
                    SET expulsado = 0
                    WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                END IF;
            END
        SQL);

        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS before_update_accion
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TRIGGER `before_update_accion` BEFORE UPDATE ON `accion` 
            FOR EACH ROW
            BEGIN
                -- Primero revertir la acción antigua
                IF OLD.tipo_de_accion = 'TA' THEN
                    IF OLD.valor = 2 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos - 2,
                            tiros2_anot = tiros2_anot - 1,
                            tiros2_int = tiros2_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    ELSEIF OLD.valor = 3 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos - 3,
                            tiros3_anot = tiros3_anot - 1,
                            tiros3_int = tiros3_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    ELSEIF OLD.valor = 1 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos - 1,
                            tiros1_anot = tiros1_anot - 1,
                            tiros1_int = tiros1_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    END IF;
                ELSEIF OLD.tipo_de_accion = 'TF' THEN
                    IF OLD.valor = 2 THEN
                        UPDATE stats_jugador 
                        SET tiros2_int = tiros2_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    ELSEIF OLD.valor = 3 THEN
                        UPDATE stats_jugador 
                        SET tiros3_int = tiros3_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    ELSEIF OLD.valor = 1 THEN
                        UPDATE stats_jugador 
                        SET tiros1_int = tiros1_int - 1
                        WHERE id_partido_id = OLD.id_partido_id AND id_jugador_id = OLD.id_jugador_id;
                    END IF;
                END IF;
            
                -- Luego aplicar la nueva acción
                IF NEW.tipo_de_accion = 'TA' THEN
                    IF NEW.valor = 2 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos + 2,
                            tiros2_anot = tiros2_anot + 1,
                            tiros2_int = tiros2_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    ELSEIF NEW.valor = 3 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos + 3,
                            tiros3_anot = tiros3_anot + 1,
                            tiros3_int = tiros3_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    ELSEIF NEW.valor = 1 THEN
                        UPDATE stats_jugador 
                        SET puntos = puntos + 1,
                            tiros1_anot = tiros1_anot + 1,
                            tiros1_int = tiros1_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    END IF;
                ELSEIF NEW.tipo_de_accion = 'TF' THEN
                    IF NEW.valor = 2 THEN
                        UPDATE stats_jugador 
                        SET tiros2_int = tiros2_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    ELSEIF NEW.valor = 3 THEN
                        UPDATE stats_jugador 
                        SET tiros3_int = tiros3_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    ELSEIF NEW.valor = 1 THEN
                        UPDATE stats_jugador 
                        SET tiros1_int = tiros1_int + 1
                        WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                    END IF;
                END IF;
            
                -- Revertir y aplicar para otros tipos de acciones (rebotes, asistencias, etc.)
                -- [Código similar para los demás tipos de acciones]
                
                -- Actualizar expulsión si corresponde
                IF (SELECT faltas_com FROM stats_jugador 
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id) = 5 THEN
                    UPDATE stats_jugador 
                    SET expulsado = 1
                    WHERE id_partido_id = NEW.id_partido_id AND id_jugador_id = NEW.id_jugador_id;
                END IF;
            END
        SQL);

        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS after_insert_partido
        SQL);

        $this->addSql(<<<'SQL'
            CREATE TRIGGER `after_insert_partido` 
            AFTER INSERT ON `partido` 
            FOR EACH ROW
            BEGIN
                -- Insertar jugadores del equipo local
                INSERT INTO stats_jugador (
                    id_partido_id, id_jugador_id, jugando, expulsado, puntos, tiros2_anot, tiros2_int, 
                    tiros3_anot, tiros3_int, tiros1_anot, tiros1_int, 
                    rebote_of, rebote_def, asistencias, tapones, robos, perdidas, 
                    faltas_com, faltas_rec, mas_menos, valoracion, minutos
                )
                SELECT 
                    NEW.id, j.id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '00:00:00'
                FROM jugador j
                WHERE j.id_equipo_id = NEW.id_equipo_local_id;
            
                -- Insertar jugadores del equipo visitante
                INSERT INTO stats_jugador (
                    id_partido_id, id_jugador_id, jugando, expulsado, puntos, tiros2_anot, tiros2_int, 
                    tiros3_anot, tiros3_int, tiros1_anot, tiros1_int, 
                    rebote_of, rebote_def, asistencias, tapones, robos, perdidas, 
                    faltas_com, faltas_rec, mas_menos, valoracion, minutos
                )
                SELECT 
                    NEW.id, j.id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '00:00:00'
                FROM jugador j
                WHERE j.id_equipo_id = NEW.id_equipo_visitante_id;
            END
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS before_insert_accion
        SQL);

        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS after_insert_accion
        SQL);

        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS before_delete_accion
        SQL);

        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS before_update_accion
        SQL);

        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS after_insert_partido
        SQL);
    }
}
