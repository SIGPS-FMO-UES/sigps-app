<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Token de Servicio de Integración de Carreras (HU-72)
    |--------------------------------------------------------------------------
    |
    | Token precompartido para autenticar las peticiones del Sistema de
    | Coordinadores y Tutores (SYS-02) al remitir expedientes avalados.
    |
    */
    'carrera_service_token' => env('CARRERA_SERVICE_TOKEN', 'carrera-service-token-secret-sigps-2026'),
];
