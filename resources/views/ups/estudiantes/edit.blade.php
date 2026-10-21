@extends('layouts.portal')
@section('title', 'Editar estudiante')
@section('heading', 'Editar estudiante')
@section('subtitle', 'Actualiza la información académica del estudiante.')
@section('page-actions')<a class="btn" href="{{ route('ups.estudiantes.show', $estudiante->id_estudiante) }}">← Volver a la ficha</a>@endsection
@section('content')
<form method="POST" action="{{ route('ups.estudiantes.update', $estudiante->id_estudiante) }}">
    @csrf
    @method('PUT')
    <section class="card">
        <div class="card-head"><h2>Datos del estudiante</h2></div>
        <div class="card-body">
            <div class="grid">
                <div class="field">
                    <label for="due">Carnet *</label>
                    <input id="due" name="due" required maxlength="50" value="{{ old('due', $estudiante->due) }}" autocomplete="off">
                </div>
                <div class="field">
                    <label for="nombre_completo">Nombre completo *</label>
                    <input id="nombre_completo" name="nombre_completo" required maxlength="255" value="{{ old('nombre_completo', $estudiante->nombre_completo) }}" autocomplete="name">
                </div>
            </div>
        </div>
    </section>
    <section class="card">
        <div class="card-head">
            <div><h2>Carrera(s){{ $carreras->isNotEmpty() ? ' *' : '' }}</h2><p class="muted" style="margin:5px 0 0;font-size:12px">Selecciona las carreras asociadas al estudiante.</p></div>
            <span class="badge">{{ $carreras->count() }} disponibles</span>
        </div>
        <div class="card-body">
            <div class="check-list">
                @php($seleccion = collect(old('carreras', $estudiante->carreras->pluck('id_carrera')->all()))->map(fn ($id) => (string) $id)->all())
                @forelse($carreras as $carrera)
                    <label class="check-option">
                        <input type="checkbox" name="carreras[]" value="{{ $carrera->id_carrera }}" @checked(in_array((string) $carrera->id_carrera, $seleccion, true))>
                        <span>{{ $carrera->nombre }}@if($carrera->departamentoRel) <small class="muted">· {{ $carrera->departamentoRel->nombre }}</small>@endif</span>
                    </label>
                @empty
                    <p class="muted">No hay carreras registradas en el catálogo.</p>
                @endforelse
            </div>
            @if($carreras->isEmpty())<div class="notice" style="margin-top:14px"><strong>Catálogo de carreras vacío</strong>El estudiante se conservará sin carrera asociada.</div>@endif
        </div>
    </section>
    <div class="form-actions">
        <a class="btn" href="{{ route('ups.estudiantes.show', $estudiante->id_estudiante) }}">Cancelar</a>
        <button class="btn btn-primary" type="submit">Guardar cambios</button>
    </div>
</form>
@endsection
