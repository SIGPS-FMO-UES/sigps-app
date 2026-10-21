<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAccion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BitacoraAuditoriaController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'usuario' => ['nullable', 'integer', 'exists:usuarios,id_usuario'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
        ]);

        $eventos = LogAccion::query()->with(['usuarioRel', 'permiso'])
            ->when($filtros['search'] ?? null, fn ($query, $search) => $query->where('accion', 'ilike', "%{$search}%"))
            ->when($filtros['usuario'] ?? null, fn ($query, $usuario) => $query->where('usuario', $usuario))
            ->when($filtros['desde'] ?? null, fn ($query, $desde) => $query->whereDate('created_at', '>=', $desde))
            ->when($filtros['hasta'] ?? null, fn ($query, $hasta) => $query->whereDate('created_at', '<=', $hasta))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.auditoria.index', [
            'eventos' => $eventos,
            'filtros' => $filtros,
            'usuarios' => Usuario::query()->whereHas('logsAcciones')->orderBy('nombre_completo')->get(),
        ]);
    }
}
