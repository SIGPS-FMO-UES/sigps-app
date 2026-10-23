<?php

namespace App\Http\Controllers\Ups;

use App\Http\Controllers\Controller;
use App\Models\AreaConocimiento;
use App\Models\Carrera;
use App\Models\Estudiante;
use App\Models\LogAccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EstudianteController extends Controller
{
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'carrera_id' => ['nullable', 'integer', 'exists:carreras,id_carrera'],
            'area_id' => ['nullable', 'integer', 'exists:areas_de_conocimiento,id_area_conocimiento'],
            'fecha' => ['nullable', 'date'],
        ]);

        $estudiantes = Estudiante::query()
            ->with(['carreras.departamentoRel', 'proyectos.areaConocimiento', 'proyectos.memoria'])
            ->when($filtros['search'] ?? null, fn ($query, $search) => $query->where(fn ($subquery) => $subquery->where('due', 'ilike', "%{$search}%")
                ->orWhere('nombre_completo', 'ilike', "%{$search}%")))
            ->when($filtros['carrera_id'] ?? null, fn ($query, $id) => $query->whereHas('carreras', fn ($carreras) => $carreras->where('carreras.id_carrera', $id)))
            ->when($filtros['area_id'] ?? null, fn ($query, $id) => $query->whereHas('proyectos', fn ($proyectos) => $proyectos->where('area_de_conocimiento', $id)))
            ->when($filtros['fecha'] ?? null, fn ($query, $fecha) => $query->whereDate('estudiantes.created_at', $fecha))
            ->orderBy('nombre_completo')
            ->paginate(12)
            ->withQueryString();

        return view('ups.estudiantes.index', [
            'estudiantes' => $estudiantes,
            'carreras' => Carrera::query()->orderBy('nombre')->get(),
            'areas' => AreaConocimiento::query()->orderBy('nombre')->get(),
            'filtros' => $filtros,
        ]);
    }

    public function create(): View
    {
        return view('ups.estudiantes.create', [
            'carreras' => Carrera::query()->orderBy('nombre')->get(),
        ]);
    }

    public function edit(string $id): View
    {
        $estudiante = Estudiante::query()
            ->with('carreras')
            ->findOrFail($id);

        return view('ups.estudiantes.edit', [
            'estudiante' => $estudiante,
            'carreras' => Carrera::query()->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $reglasCarreras = Carrera::query()->exists()
            ? ['required', 'array', 'min:1']
            : ['nullable', 'array'];

        $datos = $request->validate([
            'due' => ['required', 'string', 'max:50', 'unique:estudiantes,due'],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'carreras' => $reglasCarreras,
            'carreras.*' => ['required', 'integer', 'distinct', 'exists:carreras,id_carrera'],
        ]);

        $estudiante = DB::transaction(function () use ($datos): Estudiante {
            $carreras = $datos['carreras'] ?? [];
            unset($datos['carreras']);

            $estudiante = Estudiante::create($datos);
            $estudiante->carreras()->sync($carreras);

            LogAccion::create([
                'accion' => "Registro de estudiante {$estudiante->due}: {$estudiante->nombre_completo}",
                'usuario' => auth()->id(),
            ]);

            return $estudiante;
        });

        return redirect()->route('ups.estudiantes.show', $estudiante->id_estudiante)
            ->with('success', 'El estudiante fue registrado.');
    }

    public function buscar(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));
        if (mb_strlen($search) < 2) {
            return response()->json([]);
        }

        $driver = DB::connection()->getDriverName();
        $like = $driver === 'pgsql' ? 'ilike' : 'like';

        $estudiantes = Estudiante::query()
            ->with(['carreras:id_carrera,nombre'])
            ->where(function ($q) use ($search, $like): void {
                $q->where('due', $like, "%{$search}%")
                    ->orWhere('nombre_completo', $like, "%{$search}%");
            })
            ->orderBy('nombre_completo')
            ->limit(15)
            ->get(['id_estudiante', 'due', 'nombre_completo']);

        return response()->json($estudiantes->map(fn (Estudiante $e): array => [
            'id_estudiante' => $e->id_estudiante,
            'due' => $e->due,
            'nombre_completo' => $e->nombre_completo,
            'carreras' => $e->carreras->pluck('nombre')->all(),
        ]));
    }

    public function storeRapido(Request $request): JsonResponse
    {
        $reglasCarreras = Carrera::query()->exists()
            ? ['required', 'array', 'min:1']
            : ['nullable', 'array'];

        $datos = $request->validate([
            'due' => ['required', 'string', 'max:50', 'unique:estudiantes,due'],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'carreras' => $reglasCarreras,
            'carreras.*' => ['required', 'integer', 'distinct', 'exists:carreras,id_carrera'],
        ]);

        $estudiante = DB::transaction(function () use ($datos): Estudiante {
            $carreras = $datos['carreras'] ?? [];
            unset($datos['carreras']);

            $estudiante = Estudiante::create([
                'due' => strtoupper(trim((string) $datos['due'])),
                'nombre_completo' => trim((string) $datos['nombre_completo']),
            ]);

            if (! empty($carreras)) {
                $estudiante->carreras()->sync($carreras);
            }

            LogAccion::create([
                'accion' => "Registro rápido de estudiante {$estudiante->due}: {$estudiante->nombre_completo}",
                'usuario' => auth()->id(),
            ]);

            return $estudiante->load('carreras:id_carrera,nombre');
        });

        return response()->json([
            'success' => true,
            'message' => "Estudiante {$estudiante->due} registrado exitosamente.",
            'estudiante' => [
                'id_estudiante' => $estudiante->id_estudiante,
                'due' => $estudiante->due,
                'nombre_completo' => $estudiante->nombre_completo,
                'carreras' => $estudiante->carreras->pluck('nombre')->all(),
            ],
        ], 201);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $estudiante = Estudiante::query()->findOrFail($id);
        $reglasCarreras = Carrera::query()->exists()
            ? ['required', 'array', 'min:1']
            : ['nullable', 'array'];

        $datos = $request->validate([
            'due' => ['required', 'string', 'max:50', Rule::unique('estudiantes', 'due')->ignore($estudiante->id_estudiante, 'id_estudiante')],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'carreras' => $reglasCarreras,
            'carreras.*' => ['required', 'integer', 'distinct', 'exists:carreras,id_carrera'],
        ]);

        DB::transaction(function () use ($datos, $estudiante): void {
            $carreras = $datos['carreras'] ?? [];
            unset($datos['carreras']);

            $estudiante->update($datos);
            $estudiante->carreras()->sync($carreras);

            LogAccion::create([
                'accion' => "Actualización de estudiante {$estudiante->due}: {$estudiante->nombre_completo}",
                'usuario' => auth()->id(),
            ]);
        });

        return redirect()->route('ups.estudiantes.show', $estudiante->id_estudiante)
            ->with('success', 'Los datos del estudiante fueron actualizados.');
    }

    public function show(string $id): View
    {
        $estudiante = Estudiante::query()
            ->with(['carreras.departamentoRel', 'proyectos.areaConocimiento', 'proyectos.memoria', 'proyectos.creador'])
            ->findOrFail($id);

        return view('ups.estudiantes.show', compact('estudiante'));
    }
}
