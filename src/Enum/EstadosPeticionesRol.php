<?php

namespace App\Enum;

enum EstadosPeticionesRol: string
{
    case PENDING  = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
}