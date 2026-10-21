@extends('layouts.portal')
@section('title', 'Estudiantes')
@section('heading', 'Estudiantes')
@section('subtitle', 'Consulta estudiantes, carreras y participación en proyectos sociales.')
@section('page-actions')<a class="btn btn-primary" href="{{ route('ups.estudiantes.create') }}">＋ Añadir estudiante</a>@endsection
@section('content')
<section class="card"><div class="card-body">
    <form class="filters" method="GET" action="{{ route('ups.estudiantes.index') }}">
        <div class="field"><label for="search">Carnet o nombre</label><input id="search" name="search" value="{{ $filtros['search'] ?? '' }}" maxlength="100" placeholder="Ej. 20240001 o Ana Pérez"></div>
        <div class="field"><label for="carrera_id">Carrera</label><select id="carrera_id" name="carrera_id" onchange="this.form.requestSubmit()"><option value="">Todas las carreras</option>@foreach($carreras as $carrera)<option value="{{ $carrera->id_carrera }}" @selected(($filtros['carrera_id'] ?? '') == $carrera->id_carrera)>{{ $carrera->nombre }}</option>@endforeach</select></div>
        <div class="field"><label for="area_id">Área de proyecto</label><select id="area_id" name="area_id" onchange="this.form.requestSubmit()"><option value="">Todas las áreas</option>@foreach($areas as $area)<option value="{{ $area->id_area_conocimiento }}" @selected(($filtros['area_id'] ?? '') == $area->id_area_conocimiento)>{{ $area->nombre }}</option>@endforeach</select></div>
        <div class="field"><label for="fecha">Fecha de registro</label><input id="fecha" name="fecha" type="date" value="{{ $filtros['fecha'] ?? '' }}" onchange="this.form.requestSubmit()"></div>
    </form>
</div></section>
<section class="card"><div class="card-head"><h2>Directorio de estudiantes</h2><span class="badge">{{ $estudiantes->total() }} registros</span></div>
    <div class="table-wrap"><table class="table"><thead><tr><th>Carnet</th><th>Estudiante</th><th>Carrera(s)</th><th>Proyecto(s)</th><th>Acción</th></tr></thead><tbody>
    @forelse($estudiantes as $estudiante)<tr><td><strong>{{ $estudiante->due }}</strong></td><td>{{ $estudiante->nombre_completo }}</td><td>{{ $estudiante->carreras->pluck('nombre')->unique()->join(', ') ?: '—' }}</td><td>{{ $estudiante->proyectos->pluck('titulo')->join(', ') ?: '—' }}</td><td><a href="{{ route('ups.estudiantes.show', $estudiante->id_estudiante) }}">Ver ficha →</a></td></tr>
    @empty<tr><td class="empty" colspan="5">No hay estudiantes que coincidan con estos filtros.</td></tr>@endforelse
    </tbody></table></div>
    @if($estudiantes->hasPages())<div class="pagination"><span>Mostrando {{ $estudiantes->firstItem() }}–{{ $estudiantes->lastItem() }} de {{ $estudiantes->total() }}</span>{{ $estudiantes->onEachSide(1)->links() }}</div>@endif
</section>
@endsection
