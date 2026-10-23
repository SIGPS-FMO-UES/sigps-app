@extends('layouts.portal')
@section('title', $estudiante->nombre_completo)
@section('heading', 'Ficha del estudiante')
@section('subtitle', 'Datos acumulados de participación en proyección social.')
@section('page-actions')<a class="btn" href="{{ route('ups.estudiantes.index') }}">← Volver al directorio</a>@endsection
@section('content')
<section class="card"><div class="card-head"><div><h2>{{ $estudiante->nombre_completo }}</h2><p class="muted" style="margin:5px 0 0">Carnet {{ $estudiante->due }}</p></div><a class="btn btn-primary" href="{{ route('ups.estudiantes.edit', $estudiante->id_estudiante) }}">Editar estudiante</a></div><div class="card-body"><div class="detail-grid"><div class="detail"><small>Carnet</small><strong>{{ $estudiante->due }}</strong></div><div class="detail"><small>Carreras</small><strong>{{ $estudiante->carreras->map(fn($c) => $c->nombre . ' (' . number_format($c->horas_servicio_social) . ' h)')->unique()->join(', ') ?: 'Sin carrera registrada' }}</strong></div><div class="detail"><small>Departamento(s)</small><strong>{{ $estudiante->carreras->pluck('departamentoRel.nombre')->filter()->unique()->join(', ') ?: '—' }}</strong></div><div class="detail"><small>Proyectos registrados</small><strong>{{ $estudiante->proyectos->count() }}</strong></div></div></div></section>
<section class="card"><div class="card-head"><h2>Proyectos de servicio social</h2></div><div class="table-wrap"><table class="table"><thead><tr><th>Proyecto</th><th>Área</th><th>Inicio</th><th>Estado de memoria</th><th></th></tr></thead><tbody>
@forelse($estudiante->proyectos as $proyecto)@php($estado = $proyecto->memoria?->estado)<tr><td><strong>{{ $proyecto->titulo }}</strong></td><td>{{ $proyecto->areaConocimiento?->nombre ?? '—' }}</td><td>{{ $proyecto->fecha_inicio?->format('d/m/Y') ?? '—' }}</td><td>{{ $estado instanceof \App\Enums\EstadoMemoria ? $estado->label() : ($estado ?: 'Sin memoria') }}</td><td><a href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">Abrir proyecto →</a></td></tr>@empty<tr><td colspan="5" class="empty">Este estudiante todavía no está asociado a un proyecto.</td></tr>@endforelse
</tbody></table></div></section>
@endsection
