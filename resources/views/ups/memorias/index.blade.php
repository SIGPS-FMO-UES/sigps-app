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
                <p class="muted" style="margin:5px 0 0">{{ $disponiblesParaRevision }} expediente(s) con memoria pendiente de aprobación.</p>
            </div>
            <span class="badge">{{ $memorias->total() }} registros</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Proyecto</th>
                        <th>Expediente</th>
                        <th>Estudiante(s)</th>
                        <th>Área</th>
                        <th>Recibida</th>
                        <th>Estado</th>
                        <th>Aprobación</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($memorias as $memoria)
                        @php($estado = $memoria->estado instanceof \App\Enums\EstadoMemoria ? $memoria->estado->value : $memoria->estado)
                        <tr>
                            <td><strong>#{{ $memoria->numero_proyecto }}</strong></td>
                            <td><a href="{{ route('ups.proyectos.show', $memoria->proyecto?->id_proyecto ?? $memoria->numero_proyecto) }}">{{ $memoria->proyecto?->titulo ?? 'Proyecto no disponible' }}</a></td>
                            <td>{{ $memoria->proyecto?->estudiantes->pluck('nombre_completo')->join(', ') ?: '—' }}</td>
                            <td>{{ $memoria->proyecto?->areaConocimiento?->nombre ?? '—' }}</td>
                            <td>{{ $memoria->fecha_recibimiento?->format('d/m/Y') ?? '—' }}</td>
                            <td><span class="badge {{ $estado === 'entregada' ? 'badge-success' : (in_array($estado, ['aprobada', 'aprovada', 'lista'], true) ? 'badge-info' : 'badge-warning') }}">{{ $memoria->estado->label() }}</span></td>
                            <td>{{ $memoria->fecha_aprovado?->format('d/m/Y') ?? 'Pendiente' }}</td>
                            <td style="white-space:nowrap;display:flex;align-items:center;gap:6px">
                                <a href="{{ route('ups.memorias.show', $memoria->id_memoria) }}">Ver</a>
                                @if($memoria->documento)
                                    <a href="{{ route('ups.documentos.descargar', $memoria->id_memoria) }}" target="_blank" rel="noopener">PDF</a>
                                @endif
                                @if(in_array($estado, ['recibido', 'en_revision', 'observada'], true) && auth()->user()->hasRole('jefe_ups'))
                                    <form method="POST" action="{{ route('ups.memorias.aprobar', $memoria->id_memoria) }}" style="display:inline;margin:0;">
                                        @csrf
                                        <button class="btn btn-small btn-primary" type="submit">Aprobar</button>
                                    </form>
                                @endif
                                @if(in_array($estado, ['aprobada', 'aprovada', 'esperando_firmas', 'lista'], true))
                                    <a href="{{ route('ups.memorias.show', $memoria->id_memoria) }}" class="btn btn-small btn-primary" style="color:#ffffff!important;" title="Registrar entrega y finalizar proyecto">Entregar</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty">No hay memorias recibidas que coincidan con la búsqueda.</td></tr>
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
                <thead><tr><th>N° Proyecto</th><th>Proyecto</th><th>Área</th><th>Participantes</th><th>Inicio</th><th>Plazo de memoria</th><th>Estado del plazo</th></tr></thead>
                <tbody>
                    @forelse($proyectosSinMemoria as $proyecto)
                        @php($fechaLimite = $proyecto->fecha_inicio?->copy()->addMonths(3))
                        <tr>
                            <td><strong>#{{ $proyecto->numero_proyecto ?? $proyecto->id_proyecto }}</strong></td>
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
                        <tr><td colspan="7" class="empty">Todos los proyectos tienen una memoria registrada.</td></tr>
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
