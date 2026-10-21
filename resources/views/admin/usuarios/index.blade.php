@extends('layouts.portal')
@section('title', 'Usuarios')
@section('heading', 'Usuarios')
@section('subtitle', 'Administración técnica de las cuentas internas de la UPS.')
@section('page-actions')
    <a class="btn btn-primary" href="{{ route('admin.usuarios.create') }}">＋ Nuevo usuario</a>
@endsection
@section('content')
    <section class="card">
        <div class="card-body">
            <form class="filters" method="GET" action="{{ route('admin.usuarios.index') }}">
                <div class="field"><label for="search">Buscar usuario</label><input id="search" name="search" value="{{ $filtros['search'] ?? '' }}" maxlength="100" placeholder="Nombre, usuario o correo"></div>
                <div class="field"><label for="estado">Estado</label><select id="estado" name="estado" onchange="this.form.requestSubmit()"><option value="">Todos los estados</option><option value="activo" @selected(($filtros['estado'] ?? '') === 'activo')>Activo</option><option value="inactivo" @selected(($filtros['estado'] ?? '') === 'inactivo')>Inactivo</option></select></div>
                <div class="field"><label for="rol">Rol</label><select id="rol" name="rol" onchange="this.form.requestSubmit()"><option value="">Todos los roles</option><option value="auxiliar_ups" @selected(($filtros['rol'] ?? '') === 'auxiliar_ups')>Auxiliar UPS</option><option value="jefe_ups" @selected(($filtros['rol'] ?? '') === 'jefe_ups')>Jefatura UPS</option></select></div>
            </form>
        </div>
    </section>
    <section class="card">
        <div class="card-head"><h2>Cuentas internas</h2><span class="badge">{{ $usuarios->total() }} usuarios</span></div>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>ID</th><th>Nombre</th><th>Rol</th><th>Estado</th><th>Acción</th></tr></thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id_usuario }}</td>
                            <td><div class="row-title"><span class="avatar">{{ collect(explode(' ', $usuario->nombre_completo))->filter()->take(2)->map(fn ($parte) => mb_substr($parte, 0, 1))->implode('') }}</span><span><strong>{{ $usuario->nombre_completo }}</strong><br><small class="muted">{{ '@'.$usuario->nombre_usuario }}</small></span></div></td>
                            <td>{{ $usuario->getRoleNames()->map(fn ($role) => match($role) {'admin' => 'Administrador', 'jefe_ups' => 'Jefatura UPS', 'auxiliar_ups' => 'Auxiliar UPS', default => $role})->join(', ') ?: 'Sin rol' }}</td>
                            <td><span class="badge {{ $usuario->activo ? 'badge-success' : 'badge-danger' }}">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</span></td>
                            <td><div class="actions">@if(!$usuario->hasRole('admin'))<a class="btn btn-small" href="{{ route('admin.usuarios.edit', $usuario->id_usuario) }}">Editar</a>@endif @if((int) $usuario->getKey() === (int) auth()->id())<span class="muted">Cuenta actual</span>@elseif(!$usuario->hasRole('admin'))<form method="POST" action="{{ route('admin.usuarios.cambiarEstado', $usuario->id_usuario) }}">@csrf @method('PUT')<input type="hidden" name="activo" value="{{ $usuario->activo ? 0 : 1 }}"><button class="btn btn-small {{ $usuario->activo ? '' : 'btn-soft' }}" type="submit">{{ $usuario->activo ? 'Desactivar' : 'Activar' }}</button></form>@endif</div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">No hay cuentas que coincidan con los filtros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($usuarios->hasPages())<div class="pagination"><span>Mostrando {{ $usuarios->firstItem() }}–{{ $usuarios->lastItem() }} de {{ $usuarios->total() }}</span>{{ $usuarios->onEachSide(1)->links() }}</div>@endif
    </section>
@endsection
