<?php
// src/Enum/TipoAccion.php
namespace App\Enum;

enum TipoAccion: string
{
    case TA = 'TA'; // Tiro anotado
    case TF = 'TF'; // Tiro fallado
    case RO = 'RO'; // Rebote ofensivo
    case RD = 'RD'; // Rebote defensivo
    case AS = 'AS'; // Asistencia
    case BL = 'BL'; // Bloqueo
    case FC = 'FC'; // Falta cometida
    case FR = 'FR'; // Falta recibida
    case EX = 'EX'; // Expulsión
    case CA = 'CA'; // Cambio
}
