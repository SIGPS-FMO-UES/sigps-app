<?php

namespace App\Enums;

enum EstadoMemoria: string
{
    case Recibido = 'recibido';
    case EnRevision = 'en_revision';
    case Observada = 'observada';
    case Aprobada = 'aprobada';
    case Aprovada = 'aprovada'; // Compatibilidad con la ortografía del DBML
    case EsperandoFirmas = 'esperando_firmas';
    case Lista = 'lista';
    case Entregada = 'entregada';
    case Rechazada = 'rechazada';

    public function label(): string
    {
        return match ($this) {
            self::Recibido => 'Recibido',
            self::EnRevision => 'En Revisión',
            self::Observada => 'Observada',
            self::Aprobada, self::Aprovada => 'Aprobada',
            self::EsperandoFirmas => 'Esperando Firmas',
            self::Lista => 'Lista',
            self::Entregada => 'Entregada',
            self::Rechazada => 'Rechazada',
        };
    }
}
