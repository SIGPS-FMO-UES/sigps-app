@extends('layouts.portal')
@section('title', 'Ampliaciones y prórrogas')
@section('heading', 'Ampliaciones y prórrogas')
@section('subtitle', 'Solicitudes de modificación de plazo y resoluciones registradas.')
@section('content')
<section class="card"><div class="card-body"><form class="filters" method="GET" action="{{ route('ups.prorrogas.index') }}"><div class="field"><label for="tipo">Tipo</label><select id="tipo" name="tipo" onchange="this.form.requestSubmit()"><option value="">Todos</option><option value="ampliacion_plazo" @selected(($filtros['tipo'] ?? '') === 'ampliacion_plazo')>Ampliación</option><option value="prorroga" @selected(($filtros['tipo'] ?? '') === 'prorroga')>Prórroga</option></select></div><div class="field"><label for="estado">Estado</label><select id="estado" name="estado" onchange="this.form.requestSubmit()"><option value="">Todos</option>@foreach(['pendiente'=>'Pendiente','aprobada'=>'Aprobada','rechazada'=>'Rechazada','registrado'=>'Registrada'] as $valor=>$etiqueta)<option value="{{ $valor }}" @selected(($filtros['estado'] ?? '') === $valor)>{{ $etiqueta }}</option>@endforeach</select></div></form></div></section>
<section class="card"><div class="card-head"><h2>Solicitudes y resoluciones</h2><span class="badge">{{ $procesos->total() }} registros</span></div><div class="table-wrap"><table class="table"><thead><tr><th>Proyecto</th><th>Tipo / estado</th><th>Fechas</th><th>Motivo / respaldo</th><th>Acción</th></tr></thead><tbody>
@forelse($procesos as $proceso)
<tr>
    <td><a href="{{ route('ups.proyectos.show', $proceso->proyecto_id) }}">{{ $proceso->proyecto?->titulo ?? 'Proyecto no disponible' }}</a></td>
    <td>{{ $proceso->tipo === 'ampliacion_plazo' ? 'Ampliación' : 'Prórroga' }}<br><span class="badge {{ $proceso->estado === 'aprobada' || $proceso->estado === 'registrado' ? 'badge-success' : ($proceso->estado === 'rechazada' ? 'badge-danger' : 'badge-warning') }}">{{ ucfirst($proceso->estado) }}</span></td>
    <td>{{ $proceso->fecha_anterior?->format('d/m/Y') ?? '—' }} → {{ $proceso->fecha_nueva?->format('d/m/Y') ?? '—' }}</td>
    <td>
        {{ $proceso->motivo ?: $proceso->autorizacion ?: '—' }}
        @if($proceso->documento_respaldo)
            <br><small class="muted">Respaldo adjunto</small>
        @endif
        @if($proceso->resolucion)
            <br><small>{{ $proceso->resolucion }}</small>
        @endif
    </td>
    <td>
        @if($proceso->estado === 'pendiente' && auth()->user()->can('prorrogas.resolver'))
            <details><summary class="btn btn-small">Resolver</summary>
                <form method="POST" action="{{ route('ups.prorrogas.resolver', $proceso->id_proceso_extraordinario) }}" class="card-body" style="min-width:260px">
                    @csrf
                    <div class="field"><label for="decision-{{ $proceso->id_proceso_extraordinario }}">Decisión</label><select id="decision-{{ $proceso->id_proceso_extraordinario }}" name="decision" required><option value="aprobada">Aprobar</option><option value="rechazada">Rechazar</option></select></div>
                    <div class="field" style="margin-top:8px"><label for="resolucion-{{ $proceso->id_proceso_extraordinario }}">Resolución</label><textarea id="resolucion-{{ $proceso->id_proceso_extraordinario }}" name="resolucion" required minlength="5" maxlength="2000"></textarea></div>
                    <button class="btn btn-primary btn-small" style="margin-top:8px">Guardar resolución</button>
                </form>
            </details>
        @else
            {{ $proceso->resolutor?->nombre_completo ?? '—' }}
        @endif
    </td>
</tr>
@empty
<tr><td colspan="5" class="empty">No hay solicitudes ni prórrogas para mostrar.</td></tr>
@endforelse
</tbody></table></div>@if($procesos->hasPages())<div class="pagination"><span>Mostrando {{ $procesos->firstItem() }}–{{ $procesos->lastItem() }} de {{ $procesos->total() }}</span>{{ $procesos->onEachSide(1)->links() }}</div>@endif</section>
@endsection
