@extends('layouts.portal')

@section('title', 'Memorias')
@section('heading', 'Memorias')
@section('subtitle', 'Revisa las memorias finales recibidas dentro de los expedientes de proyectos.')

@section('content')
    <section class="card">
        <div class="card-body">
            <form class="filters" method="GET" action="{{ route('ups.memorias.index') }}">
                <div class="field">
                    <label for="search">Buscar memoria</label>
                    <input id="search" name="search" value="{{ $filtros['search'] ?? '' }}" maxlength="100" placeholder="Proyecto, carnet o estudiante">
                </div>
                <div class="field">
                    <label for="estado">Estado de revisión</label>
                    <select name="estado" id="estado" onchange="this.form.requestSubmit()">
                        <option value="">Todos los estados</option>
                        @foreach(\App\Enums\EstadoMemoria::cases() as $estado)
                            <option value="{{ $estado->value }}" @selected(($filtros['estado'] ?? '') === $estado->value)>{{ $estado->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="fecha">Fecha de recepción</label>
                    <input type="date" name="fecha" id="fecha" value="{{ $filtros['fecha'] ?? '' }}" onchange="this.form.requestSubmit()">
                </div>
                <button class="btn" type="submit">Buscar</button>
                @if(count($filtros))
                    <a class="btn" href="{{ route('ups.memorias.index') }}">Limpiar filtros</a>
                @endif
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-head">
            <div>
                <h2>Bandeja de memorias finales</h2>
                <p class="muted" style="margin:5px 0 0">{{ $disponiblesParaRevision }} expediente(s) esperan o requieren una revisión documental.</p>
            </div>
            <span class="badge">{{ $memorias->total() }} registros</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Expediente</th>
                        <th>Estudiante(s)</th>
                        <th>Área</th>
                        <th>Recibida</th>
                        <th>Estado</th>
                        <th>Última revisión</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($memorias as $memoria)
                        @php($estado = $memoria->estado instanceof \App\Enums\EstadoMemoria ? $memoria->estado->value : $memoria->estado)
                        <tr>
                            <td><a href="{{ route('ups.proyectos.show', $memoria->proyecto_id) }}">{{ $memoria->proyecto?->titulo ?? 'Proyecto no disponible' }}</a></td>
                            <td>{{ $memoria->proyecto?->estudiantes->pluck('nombre_completo')->join(', ') ?: '—' }}</td>
                            <td>{{ $memoria->proyecto?->areaConocimiento?->nombre ?? '—' }}</td>
                            <td>{{ $memoria->fecha_recibimiento?->format('d/m/Y') ?? '—' }}</td>
                            <td><span class="badge {{ $estado === 'observada' ? 'badge-warning' : ($estado === 'aprobada' || $estado === 'aprovada' ? 'badge-success' : 'badge-info') }}">{{ $memoria->estado->label() }}</span></td>
                            <td>{{ $memoria->revisiones->first()?->created_at?->format('d/m/Y H:i') ?? 'Sin dictamen' }}</td>
                            <td style="white-space:nowrap">
                                <a href="{{ route('ups.memorias.show', $memoria->id_memoria) }}">Ver expediente</a>
                                @if(in_array($estado, ['recibido', 'en_revision', 'observada'], true))
                                    <a href="{{ route('ups.auditoria.show', $memoria->id_memoria) }}">Auditar sellos y tinta →</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">No hay memorias recibidas que coincidan con la búsqueda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($memorias->hasPages())
            <div class="pagination">
                <span>Mostrando {{ $memorias->firstItem() }}–{{ $memorias->lastItem() }} de {{ $memorias->total() }}</span>
                {{ $memorias->onEachSide(1)->links() }}
            </div>
        @endif
    </section>

    <section class="card">
        <div class="card-head">
            <div>
                <h2>Proyectos sin memoria final recibida</h2>
                <p class="muted" style="margin:5px 0 0">Control del plazo de referencia de tres meses desde el inicio del proyecto.</p>
            </div>
            <span class="badge">{{ $proyectosSinMemoria->total() }} proyectos</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Proyecto</th><th>Área</th><th>Participantes</th><th>Inicio</th><th>Plazo de memoria</th><th>Estado del plazo</th></tr></thead>
                <tbody>
                    @forelse($proyectosSinMemoria as $proyecto)
                        @php($fechaLimite = $proyecto->fecha_inicio?->copy()->addMonths(3))
                        <tr>
                            <td><a href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">{{ $proyecto->titulo }}</a></td>
                            <td>{{ $proyecto->areaConocimiento?->nombre ?? '—' }}</td>
                            <td>{{ $proyecto->estudiantes->pluck('nombre_completo')->join(', ') ?: '—' }}</td>
                            <td>{{ $proyecto->fecha_inicio?->format('d/m/Y') ?? '—' }}</td>
                            <td>{{ $fechaLimite?->format('d/m/Y') ?? '—' }}</td>
                            <td>
                                @if($fechaLimite?->isPast())
                                    <span class="badge badge-danger">Plazo vencido</span>
                                @else
                                    <span class="badge badge-warning">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">Todos los proyectos tienen una memoria registrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($proyectosSinMemoria->hasPages())
            <div class="pagination"><span>Mostrando {{ $proyectosSinMemoria->firstItem() }}–{{ $proyectosSinMemoria->lastItem() }} de {{ $proyectosSinMemoria->total() }}</span>{{ $proyectosSinMemoria->onEachSide(1)->links() }}</div>
        @endif
    </section>

    <p class="muted" style="font-size:12px">Las memorias llegan al expediente por la integración institucional. La UPS revisa visualmente el PDF y registra el dictamen; esta pantalla no reemplaza la recepción desde el sistema de coordinación.</p>
@endsection
