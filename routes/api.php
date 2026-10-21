<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Backoffice UPS (SYS-01)
|--------------------------------------------------------------------------
|
| Rutas API RESTful bajo prefijo /api/v1/.
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/ping', function () {
        return response()->json([
            'status' => 'ok',
            'sistema' => 'SIGPS Backoffice UPS (SYS-01)',
            'timestamp' => now()->toIso8601String(),
        ]);
    });
});
