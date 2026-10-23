<?php

namespace App\Http\Controllers\Ups;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class NotificacionController extends Controller
{
    public function marcarLeida(string $id): RedirectResponse
    {
        $notificacion = auth()->user()->notifications()->whereKey($id)->firstOrFail();
        $notificacion->markAsRead();

        $proyectoId = $notificacion->data['proyecto_id'] ?? null;
        if ($proyectoId) {
            return to_route('ups.proyectos.show', $proyectoId);
        }

        return to_route('ups.bandeja.index');
    }

    public function marcarTodasLeidas(): RedirectResponse
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Todas las notificaciones se marcaron como leídas.');
    }
}
