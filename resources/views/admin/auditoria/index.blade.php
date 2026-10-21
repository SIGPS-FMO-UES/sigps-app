@extends('layouts.portal')
@section('title', 'Auditoría administrativa')
@section('heading', 'Auditoría administrativa')
@section('subtitle', 'Consulta de acciones registradas por el personal en el sistema.')
@section('content')
<section class="notice"><strong>Bitácora de consulta</strong>Los eventos se presentan en orden cronológico y esta pantalla no ofrece acciones para editarlos o eliminarlos.</section>
<section class="card" style="margin-top:18px"><div class="card-body"><form class="filters" method="GET" action="{{ route('admin.auditoria.index') }}">
    <div class="field"><label for="search">Buscar acción</label><input id="search" name="search" value="{{ $filtros['search'] ?? '' }}" maxlength="150" placeholder="Descripción del evento"></div>
    <div class="field"><label for="usuario">Usuario</label><select id="usuario" name="usuario" onchange="this.form.requestSubmit()"><option value="">Todos</option>@foreach($usuarios as $usuario)<option value="{{ $usuario->id_usuario }}" @selected(($filtros['usuario'] ?? '') == $usuario->id_usuario)>{{ $usuario->nombre_completo }}</option>@endforeach</select></div>
    <div class="field"><label for="desde">Desde</label><input id="desde" name="desde" type="date" value="{{ $filtros['desde'] ?? '' }}"></div>
    <div class="field"><label for="hasta">Hasta</label><input id="hasta" name="hasta" type="date" value="{{ $filtros['hasta'] ?? '' }}"></div>
    <button class="btn btn-primary" type="submit">Aplicar filtros</button><a class="btn" href="{{ route('admin.auditoria.index') }}">Limpiar</a>
</form></div></section>
<section class="card"><div class="card-head"><h2>Eventos registrados</h2><span class="badge">{{ $eventos->total() }} eventos</span></div><div class="table-wrap"><table class="table"><thead><tr><th>Fecha y hora</th><th>Usuario</th><th>Acción</th><th>Permiso</th></tr></thead><tbody>
@forelse($eventos as $evento)<tr><td>{{ $evento->created_at?->format('d/m/Y H:i:s') ?? '—' }}</td><td><strong>{{ $evento->usuarioRel?->nombre_completo ?? 'Usuario no disponible' }}</strong>@if($evento->usuarioRel)<br><small class="muted">{{ '@'.$evento->usuarioRel->nombre_usuario }}</small>@endif</td><td>{{ $evento->accion }}</td><td>{{ $evento->permiso?->name ?? '—' }}</td></tr>@empty<tr><td colspan="4" class="empty">No hay eventos que coincidan con los filtros.</td></tr>@endforelse
</tbody></table></div>@if($eventos->hasPages())<div class="pagination"><span>Mostrando {{ $eventos->firstItem() }}–{{ $eventos->lastItem() }} de {{ $eventos->total() }}</span>{{ $eventos->onEachSide(1)->links() }}</div>@endif</section>
@endsection
