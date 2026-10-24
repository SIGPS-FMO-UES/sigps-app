<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use App\Models\Departamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarreraDepartamentoController extends Controller
{
    public function index(): View
    {
        return view('admin.catalogos.index', [
            'departamentos' => Departamento::query()->withCount('carreras')->orderBy('nombre')->paginate(100, ['*'], 'departamentos_page')->withQueryString(),
            'carreras' => Carrera::query()->with('departamentoRel')->withCount('estudiantes')->orderBy('nombre')->paginate(100, ['*'], 'carreras_page')->withQueryString(),
            'todosLosDepartamentos' => Departamento::query()->orderBy('nombre')->get(),
        ]);
    }

    public function storeDepartamento(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150', Rule::unique('departamentos', 'nombre')],
        ]);

        Departamento::query()->create($data);

        return to_route('admin.catalogos.index')->with('success', 'Departamento creado correctamente.');
    }

    public function updateDepartamento(Request $request, string $id): RedirectResponse
    {
        $departamento = Departamento::query()->findOrFail($id);
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150', Rule::unique('departamentos', 'nombre')->ignore($departamento->id_departamento, 'id_departamento')],
        ]);

        $departamento->update($data);

        return to_route('admin.catalogos.index')->with('success', 'Departamento actualizado correctamente.');
    }

    public function destroyDepartamento(string $id): RedirectResponse
    {
        $departamento = Departamento::query()->withCount('carreras')->findOrFail($id);
        if ($departamento->carreras_count > 0) {
            return back()->withErrors(['departamento' => 'No se puede eliminar: primero reasigna o elimina las carreras de este departamento.']);
        }

        $departamento->delete();

        return to_route('admin.catalogos.index')->with('success', 'Departamento eliminado correctamente.');
    }

    public function storeCarrera(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150', Rule::unique('carreras', 'nombre')->where(fn ($query) => $query->where('departamento', $request->input('departamento')))],
            'departamento' => ['required', 'integer', 'exists:departamentos,id_departamento'],
            'horas_servicio_social' => ['required', 'integer', 'min:1', 'max:5000'],
        ]);

        Carrera::query()->create($data);

        return to_route('admin.catalogos.index')->with('success', 'Carrera creada correctamente.');
    }

    public function updateCarrera(Request $request, string $id): RedirectResponse
    {
        $carrera = Carrera::query()->findOrFail($id);
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150', Rule::unique('carreras', 'nombre')->where(fn ($query) => $query->where('departamento', $request->input('departamento')))->ignore($carrera->id_carrera, 'id_carrera')],
            'departamento' => ['required', 'integer', 'exists:departamentos,id_departamento'],
            'horas_servicio_social' => ['required', 'integer', 'min:1', 'max:5000'],
        ]);

        $carrera->update($data);

        return to_route('admin.catalogos.index')->with('success', 'Carrera actualizada correctamente.');
    }

    public function destroyCarrera(string $id): RedirectResponse
    {
        $carrera = Carrera::query()->withCount('estudiantes')->findOrFail($id);
        if ($carrera->estudiantes_count > 0) {
            return back()->withErrors(['carrera' => 'No se puede eliminar: hay estudiantes inscritos en esta carrera.']);
        }

        $carrera->delete();

        return to_route('admin.catalogos.index')->with('success', 'Carrera eliminada correctamente.');
    }
}
