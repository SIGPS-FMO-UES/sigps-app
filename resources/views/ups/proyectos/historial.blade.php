@extends('layouts.portal')

@section('title', 'Historial · '.$proyecto->titulo)
@section('heading', 'Historial del proyecto')
@section('subtitle', 'Trazabilidad cronológica de fases, cambios y procesos extraordinarios del expediente.')

@section('page-actions')
    <a class="btn" href="{{ route('ups.proyectos.show', $proyecto->id_proyecto) }}">← Volver al proyecto</a>
@endsection

@section('content')
    <section class="card">
        <div class="card-head">
            <div>
                <h2>{{ $proyecto->titulo }}</h2>
                <p class="muted" style="margin:5px 0 0">Expediente #{{ $proyecto->id_proyecto }} · {{ $proyecto->areaConocimiento?->nombre ?? 'Sin área asignada' }}</p>
            </div>
            <span class="badge {{ $proyecto->estado_expediente === 'finalizado' ? 'badge-success' : ($proyecto->estado_expediente === 'cancelado' ? 'badge-danger' : 'badge-info') }}">
                {{ ['en_tramite' => 'En trámite', 'finalizado' => 'Finalizado', 'cancelado' => 'Cancelado'][$proyecto->estado_expediente] ?? ucfirst($proyecto->estado_expediente) }}
            </span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr><th>Fecha</th><th>Cambio realizado</th><th>Fase / estado</th><th>Responsable</th><th>Observaciones</th></tr>
                </thead>
                <tbody>
                    @forelse($historialProyecto as $movimiento)
                        <tr>
                            <td>{{ $movimiento['fecha']?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td><strong>{{ $movimiento['cambio'] }}</strong></td>
                            <td>
                                @if($movimiento['fase'])
                                    {{ $movimiento['fase'] }}
                                @elseif($movimiento['estado'])
                                    <span class="badge {{ in_array($movimiento['estado'], ['Aprobada', 'Registrado'], true) ? 'badge-success' : ($movimiento['estado'] === 'Rechazada' ? 'badge-danger' : 'badge-warning') }}">{{ $movimiento['estado'] }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $movimiento['responsable'] }}</td>
                            <td>{{ $movimiento['detalle'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">Todavía no hay movimientos registrados para este proyecto.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
