<?php

namespace App\Enums;

enum TipoPlantilla: string
{
    case ArchivoEjemplo = 'archivo_ejemplo';
    case Autorellenable = 'autorellenable';

    public function label(): string
    {
        return match ($this) {
            self::ArchivoEjemplo => 'Archivo de Ejemplo',
            self::Autorellenable => 'Autorellenable',
        };
    }
}
