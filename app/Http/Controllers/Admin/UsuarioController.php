<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAccion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function create(): View
    {
        return view('admin.usuarios.create', ['usuario' => new User]);
    }

    public function edit(string $id): View
    {
        $usuario = User::with('roles')->findOrFail($id);

        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'estado' => ['nullable', 'in:activo,inactivo'],
            'rol' => ['nullable', 'in:auxiliar_ups,jefe_ups'],
        ]);

        $usuarios = User::query()->with('roles')
            ->when($filtros['search'] ?? null, fn ($query, $search) => $query->where(fn ($subquery) => $subquery->where('nombre_completo', 'ilike', "%{$search}%")
                ->orWhere('nombre_usuario', 'ilike', "%{$search}%")
                ->orWhere('email', 'ilike', "%{$search}%")))
            ->when(isset($filtros['estado']), fn ($query) => $query->where('activo', $filtros['estado'] === 'activo'))
            ->when($filtros['rol'] ?? null, fn ($query, $rol) => $query->role($rol))
            ->orderBy('nombre_completo')->paginate(100)->withQueryString();

        return view('admin.usuarios.index', compact('usuarios', 'filtros'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_usuario' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:usuarios,nombre_usuario'],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
            'rol' => ['required', 'in:auxiliar_ups,jefe_ups'],
            'activo' => ['required', 'boolean'],
        ]);
        $rol = $datos['rol'];
        unset($datos['rol']);

        DB::transaction(function () use ($datos, $rol): void {
            $usuario = User::create($datos);
            $usuario->assignRole($rol);
            LogAccion::create(['accion' => "Creación de usuario {$usuario->nombre_usuario} con rol {$rol}", 'usuario' => auth()->id()]);
        });

        return redirect()->route('admin.usuarios.index')->with('success', 'La cuenta fue creada y el rol asignado.');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $usuario = User::with('roles')->findOrFail($id);
        abort_if((int) $usuario->id_usuario === (int) auth()->id() && ! $request->boolean('activo'), 422, 'No puedes desactivar tu propia cuenta.');

        $datos = $request->validate([
            'nombre_usuario' => ['required', 'string', 'max:255', 'alpha_dash', "unique:usuarios,nombre_usuario,{$usuario->id_usuario},id_usuario"],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', "unique:usuarios,email,{$usuario->id_usuario},id_usuario"],
            'password' => ['nullable', 'string', 'min:10', 'confirmed'],
            'rol' => ['required', 'in:auxiliar_ups,jefe_ups'],
            'activo' => ['required', 'boolean'],
        ]);
        $rol = $datos['rol'];
        unset($datos['rol']);
        if (blank($datos['password'] ?? null)) {
            unset($datos['password']);
        }

        DB::transaction(function () use ($usuario, $datos, $rol): void {
            $usuario->update($datos);
            $usuario->syncRoles([$rol]);
            LogAccion::create(['accion' => "Actualización de usuario {$usuario->nombre_usuario}", 'usuario' => auth()->id()]);
        });

        return redirect()->route('admin.usuarios.index')->with('success', 'Se actualizaron los datos del usuario.');
    }

    public function cambiarEstado(Request $request, string $id): RedirectResponse
    {
        $datos = $request->validate(['activo' => ['required', 'boolean']]);
        $usuario = User::findOrFail($id);
        abort_if((int) $usuario->getKey() === (int) auth()->id() && ! $request->boolean('activo'), 422, 'No puedes desactivar tu propia cuenta.');

        $usuario->update(['activo' => $request->boolean('activo')]);
        LogAccion::create(['accion' => ($usuario->activo ? 'Activación' : 'Desactivación')." de usuario {$usuario->nombre_usuario}", 'usuario' => auth()->id()]);

        return back()->with('success', 'El estado de la cuenta fue actualizado.');
    }
}
