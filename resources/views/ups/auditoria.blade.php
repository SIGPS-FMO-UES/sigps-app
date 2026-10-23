@extends('layouts.portal')

@section('title', 'Auditar memoria')
@section('heading', 'Auditoría documental')
@section('subtitle', 'Coteja visualmente el PDF de la memoria, sus sellos y la tinta azul.')

@section('page-actions')
    <a class="btn" href="{{ route('ups.memorias.show', $memoria->id_memoria) }}">← Expediente de memoria</a>
@endsection

@section('content')
    <section class="card">
        <div class="card-head">
            <div>
                <h2>{{ $memoria->proyecto?->titulo ?? 'Proyecto no disponible' }}</h2>
                <p class="muted" style="margin:5px 0 0">Proyecto #{{ $memoria->numero_proyecto }} · Expediente #{{ $memoria->proyecto?->id_proyecto }} · Intento {{ $memoria->intento }} · Recibida {{ $memoria->fecha_recibimiento?->format('d/m/Y') ?? '—' }}</p>
            </div>
            <span class="badge badge-info">En auditoría</span>
        </div>
        <div class="card-body audit-layout">
            <section class="audit-document" aria-label="Vista del documento">
                @if($documentoDisponible)
                    <iframe title="PDF de la memoria final" src="{{ route('ups.documentos.descargar', $memoria->id_memoria) }}"></iframe>
                @else
                    <div class="notice"><strong>PDF no disponible</strong>El documento debe recibirse por la integración del sistema de coordinación y almacenarse en el expediente antes de auditarlo.</div>
                @endif
            </section>

            <section class="audit-form-panel">
                <h2>Dictamen de revisión</h2>
                <p class="muted">Revisa todas las páginas del expediente PDF antes de emitir el resultado.</p>
                @if($documentoDisponible)
                    <form method="POST" action="{{ route('ups.auditoria.store', $memoria->id_memoria) }}">
                        @csrf

                        <div class="field">
                            <label for="sellos_verificados">¿Se verificaron los sellos?</label>
                            <select id="sellos_verificados" name="sellos_verificados" required>
                                <option value="">Selecciona una opción</option>
                                <option value="1" @selected(old('sellos_verificados') === '1')>Sí, están verificados</option>
                                <option value="0" @selected(old('sellos_verificados') === '0')>No, presentan observaciones</option>
                            </select>
                            @error('sellos_verificados')<small class="form-error">{{ $message }}</small>@enderror
                        </div>

                        <div class="field" style="margin-top:14px">
                            <label for="tinta_azul_verificada">¿Se verificó la tinta azul?</label>
                            <select id="tinta_azul_verificada" name="tinta_azul_verificada" required>
                                <option value="">Selecciona una opción</option>
                                <option value="1" @selected(old('tinta_azul_verificada') === '1')>Sí, está verificada</option>
                                <option value="0" @selected(old('tinta_azul_verificada') === '0')>No, presenta observaciones</option>
                            </select>
                            @error('tinta_azul_verificada')<small class="form-error">{{ $message }}</small>@enderror
                        </div>

                        <div class="field" style="margin-top:14px">
                            <label for="resultado">Resultado</label>
                            <select id="resultado" name="resultado" required>
                                <option value="">Selecciona el dictamen</option>
                                <option value="aprobado" @selected(old('resultado') === 'aprobado')>Aprobar memoria</option>
                                <option value="observado" @selected(old('resultado') === 'observado')>Observar memoria</option>
                            </select>
                            @error('resultado')<small class="form-error">{{ $message }}</small>@enderror
                        </div>

                        <div class="field" style="margin-top:14px">
                            <label for="observaciones">Motivo / observaciones <span id="motivo-requerido" class="muted">(obligatorio si se observa)</span></label>
                            <textarea id="observaciones" name="observaciones" maxlength="5000" rows="5" placeholder="Describe claramente qué debe corregirse">{{ old('observaciones') }}</textarea>
                            @error('observaciones')<small class="form-error">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-actions" style="margin-top:16px">
                            <a class="btn" href="{{ route('ups.memorias.show', $memoria->id_memoria) }}">Cancelar</a>
                            <button class="btn btn-primary" type="submit">Registrar dictamen</button>
                        </div>
                    </form>
                @endif
            </section>
        </div>
    </section>

    <section class="card">
        <div class="card-head"><div><h2>Revisiones anteriores</h2><p class="muted" style="margin:5px 0 0">Trazabilidad del expediente.</p></div><span class="badge">{{ $memoria->revisiones->count() }}</span></div>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Fecha</th><th>Auditor</th><th>Resultado</th><th>Sellos</th><th>Tinta azul</th><th>Observaciones</th></tr></thead>
                <tbody>
                    @forelse($memoria->revisiones as $revision)
                        <tr>
                            <td>{{ $revision->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>{{ $revision->auditor?->nombre_completo ?? '—' }}</td>
                            <td>{{ ucfirst($revision->resultado) }}</td>
                            <td>{{ $revision->sellos_verificados ? 'Sí' : 'No' }}</td>
                            <td>{{ $revision->tinta_azul_verificada ? 'Sí' : 'No' }}</td>
                            <td>{{ $revision->observaciones ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">No hay dictámenes previos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
<style>
    .audit-layout { display:grid; grid-template-columns:minmax(0,1.55fr) minmax(300px,.8fr); gap:18px; align-items:start; }
    .audit-document { min-height:400px; }
    .audit-document iframe { width:100%; height:min(72vh,850px); min-height:600px; border:1px solid #dee3ea; border-radius:8px; background:#f8fafc; }
    .audit-form-panel { padding:4px 2px; }
    .audit-form-panel h2 { margin:0; font-size:15px; }
    .audit-form-panel>p { margin:7px 0 18px; font-size:12px; line-height:1.5; }
    .form-error { color:#ad3040; font-size:11px; }
    @media(max-width:960px) { .audit-layout { grid-template-columns:1fr; } .audit-document iframe { min-height:420px; height:65vh; } }
</style>

<script>
    (() => {
        const result = document.getElementById('resultado');
        const observations = document.getElementById('observaciones');

        if (!result || !observations) return;

        const syncRequirement = () => {
            observations.required = result.value === 'observado';
        };

        result.addEventListener('change', syncRequirement);
        syncRequirement();
    })();
</script>
@endsection
