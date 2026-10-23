@extends('layouts.portal')

@section('title', $proyecto->titulo)
@section('heading', 'Expediente del proyecto')
@section('subtitle', 'Ficha administrativa, participantes y situación documental.')

@section('page-actions')
    <a class="btn" href="{{ route('ups.proyectos.index') }}">← Proyectos</a>
    <a class="btn" href="{{ route('ups.proyectos.historial', $proyecto->id_proyecto) }}">Ver historial</a>
    <a class="btn" href="{{ route('ups.proyectos.documentos', $proyecto->id_proyecto) }}">Expediente documental</a>
    @if($proyecto->estado_expediente === 'en_tramite')
        <details class="project-actions-menu">
            <summary class="btn">Más acciones</summary>
            <div class="project-actions-popover" role="menu" aria-label="Acciones del proyecto">
                <button type="button" role="menuitem" data-project-modal="modal-ampliar">Ampliar plazo</button>
                @can('prorrogas.resolver')<button type="button" role="menuitem" data-project-modal="modal-prorroga">Registrar prórroga</button>@endcan
                <button type="button" role="menuitem" data-project-modal="modal-retirar">Retirar estudiante</button>
                @if(auth()->user()->hasRole('jefe_ups'))<button type="button" role="menuitem" class="project-actions-danger" data-project-modal="modal-cancelar">Cancelar proyecto</button>@endif
            </div>
        </details>
    @endif
    @if($proyecto->estado_expediente === 'en_tramite')<a class="btn btn-primary" href="{{ route('ups.proyectos.edit', $proyecto->id_proyecto) }}">Editar datos</a>@endif
@endsection

@section('content')
    <section class="card">
        <div class="card-head">
            <div>
                <h2>{{ $proyecto->titulo }}</h2>
                <p class="muted" style="margin:5px 0 0">Número de proyecto #{{ $proyecto->numero_proyecto ?? $proyecto->id_proyecto }} · Expediente #{{ $proyecto->id_proyecto }}</p>
            </div>
            <span class="badge {{ $proyecto->estado_expediente === 'finalizado' ? 'badge-success' : ($proyecto->estado_expediente === 'cancelado' ? 'badge-danger' : 'badge-info') }}">{{ ['en_tramite'=>'En trámite','finalizado'=>'Finalizado','cancelado'=>'Cancelado'][$proyecto->estado_expediente] ?? $proyecto->estado_expediente }}</span>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail"><small>N° Proyecto</small><strong>#{{ $proyecto->numero_proyecto ?? '—' }}</strong></div>
                <div class="detail"><small>Área</small><strong>{{ $proyecto->areaConocimiento?->nombre ?? '—' }}</strong></div>
                <div class="detail"><small>Fecha de inicio</small><strong>{{ $proyecto->fecha_inicio?->format('d/m/Y') ?? '—' }}</strong></div>
                <div class="detail"><small>Fecha final</small><strong>{{ $proyecto->fecha_final?->format('d/m/Y') ?? 'Pendiente' }}</strong></div>
                <div class="detail"><small>Registrado por</small><strong>{{ $proyecto->creador?->nombre_completo ?? '—' }}</strong></div>
                <div class="detail"><small>Docente tutor</small><strong>{{ $proyecto->tutor ?: '—' }}</strong></div>
                <div class="detail"><small>Subcoordinador en expediente</small><strong>{{ $proyecto->subcoordinador }}</strong></div>
                <div class="detail"><small>Referencia documental</small><strong>{{ $proyecto->documento ?: '—' }}</strong></div>
                <div class="detail"><small>Participantes</small><strong>{{ $proyecto->estudiantes->count() }}</strong></div>
                <div class="detail"><small>Fase actual</small><strong>{{ ['inscripcion'=>'Inscripción','anteproyecto'=>'Anteproyecto','avance_50'=>'Avance 50%','memoria_final'=>'Memoria final','finalizacion'=>'Finalización'][$proyecto->fase_actual] ?? $proyecto->fase_actual }}</strong></div>
            </div>
        </div>
    </section>

    <section class="card"><div class="card-head"><h2>Ciclo de vida del proyecto</h2><span class="badge">{{ $proyecto->estado_expediente === 'en_tramite' ? 'En curso' : ucfirst($proyecto->estado_expediente) }}</span></div><div class="card-body"><div class="actions" style="flex-wrap:wrap">@foreach(['inscripcion'=>'Inscripción','anteproyecto'=>'Anteproyecto','avance_50'=>'Avance 50%','memoria_final'=>'Memoria final'] as $fase=>$etiqueta)<button class="btn {{ $proyecto->fase_actual === $fase ? 'btn-soft' : '' }}" type="button" data-project-modal="modal-fase-{{ $fase }}">{{ $etiqueta }}</button>@endforeach</div><p class="muted" style="margin-bottom:0">La fase actual se indica en la ficha y cada admisión queda registrada en el historial.</p></div></section>

    <section class="card">
        <div class="card-head"><h2>Estudiantes participantes</h2><div class="actions">@if($proyecto->estado_expediente === 'en_tramite')<a class="btn btn-small" href="{{ route('ups.proyectos.edit', $proyecto->id_proyecto) }}#estudiantes">Asociar estudiantes</a>@endif</div></div>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Carnet</th><th>Nombre</th><th>Carrera(s)</th><th></th></tr></thead>
                <tbody>
                    @forelse($proyecto->estudiantes as $estudiante)
                        <tr>
                            <td><strong>{{ $estudiante->due }}</strong></td>
                            <td>{{ $estudiante->nombre_completo }}</td>
                            <td>{{ $estudiante->carreras->pluck('nombre')->unique()->join(', ') ?: '—' }}</td>
                            <td><a href="{{ route('ups.estudiantes.show', $estudiante->id_estudiante) }}">Ver ficha →</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty">No hay participantes asociados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if($proyecto->memoria)
        <section class="card">
            <div class="card-head">
                <div><h2>Memoria final</h2><p class="muted" style="margin:5px 0 0">Estado documental del proyecto.</p></div>
                <a href="{{ route('ups.memorias.show', $proyecto->memoria->id_memoria) }}">Abrir memoria →</a>
            </div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail"><small>Estado</small><strong>{{ $proyecto->memoria->estado->label() }}</strong></div>
                    <div class="detail"><small>Recibida</small><strong>{{ $proyecto->memoria->fecha_recibimiento?->format('d/m/Y') ?? '—' }}</strong></div>
                    <div class="detail"><small>Intento de entrega</small><strong>{{ $proyecto->memoria->intento }}</strong></div>
                    <div class="detail"><small>Último dictamen</small><strong>{{ $proyecto->memoria->revisiones->first()?->created_at?->format('d/m/Y H:i') ?? 'Sin revisión' }}</strong></div>
                </div>
                @if($proyecto->memoria->observaciones)<p class="muted">{{ $proyecto->memoria->observaciones }}</p>@endif
            </div>
        </section>
    @else
        @php
            $fechaLimiteMemoria = $proyecto->fecha_inicio?->copy()->addMonths(3);
        @endphp
        <section class="card">
            <div class="card-head"><div><h2>Memoria final</h2><p class="muted" style="margin:5px 0 0">La memoria llegará al expediente por la integración institucional.</p></div><a href="{{ route('ups.memorias.index') }}">Bandeja de memorias →</a></div>
            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail"><small>Estado</small><strong>Sin memoria recibida</strong></div>
                    <div class="detail"><small>Plazo de referencia</small><strong>{{ $fechaLimiteMemoria?->format('d/m/Y') ?? '—' }}</strong></div>
                </div>
                @if($fechaLimiteMemoria?->isPast())
                    <div class="notice" style="margin-top:14px"><strong>Memoria pendiente</strong>El plazo de referencia de tres meses desde el inicio ya venció.</div>
                @endif
            </div>
        </section>
    @endif

        @foreach(['inscripcion'=>'Inscripción','anteproyecto'=>'Anteproyecto','avance_50'=>'Avance 50%','memoria_final'=>'Memoria final'] as $fase=>$etiqueta)
            @php
                $faseEsActual = $proyecto->fase_actual === $fase;
                $observacionFase = $proyecto->historialFases->where('fase', $fase)->where('evento', 'observaciones_guardadas')->first()?->observaciones;
                $formKeyFase = 'fase_'.$fase;
            @endphp
            <dialog class="project-action-dialog" id="modal-fase-{{ $fase }}" aria-labelledby="modal-fase-{{ $fase }}-title">
                <form method="POST" action="{{ route('ups.proyectos.fase.observaciones', [$proyecto->id_proyecto, $fase]) }}">
                    @csrf <input type="hidden" name="form_key" value="{{ $formKeyFase }}">
                    <div class="project-dialog-head"><h2 id="modal-fase-{{ $fase }}-title">{{ $etiqueta }}</h2><button type="button" class="project-dialog-close" data-project-close aria-label="Cerrar">×</button></div>
                    <div class="project-phase-summary"><strong>{{ $proyecto->titulo }}</strong><span>Estado de la etapa: {{ $faseEsActual ? 'En revisión / pendiente de admisión' : 'Fase completada' }}</span></div>
                    <div class="project-phase-documents"><strong>Documentos de esta etapa</strong>
                        @forelse($proyecto->documentos->where('fase', $fase) as $documento)
                            <a class="btn btn-small" href="{{ route('ups.documentos-proyecto.descargar', $documento->id_documento_proyecto) }}" target="_blank" rel="noopener">Ver documento · {{ $documento->nombre_original }}</a>
                        @empty
                            <span class="muted">No hay documentos asociados a esta fase.</span>
                        @endforelse
                        @if($fase === 'memoria_final' && $proyecto->memoria)<a class="btn btn-small" href="{{ route('ups.memorias.show', $proyecto->memoria->id_memoria) }}">Abrir memoria final ({{ $proyecto->memoria->estado->label() }})</a>@endif
                    </div>
                    <div class="field"><label for="observaciones-{{ $fase }}">Observaciones</label><textarea id="observaciones-{{ $fase }}" name="observaciones" data-phase-observations="{{ $fase }}" @required($faseEsActual && $proyecto->estado_expediente === 'en_tramite') @readonly(!$faseEsActual || $proyecto->estado_expediente !== 'en_tramite') minlength="3" maxlength="3000" placeholder="Escriba las observaciones">{{ old('form_key') === $formKeyFase ? old('observaciones') : ($observacionFase ?? '') }}</textarea>@error('observaciones') @if(old('form_key') === $formKeyFase)<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                    <div class="form-actions"><button type="button" class="btn" data-project-close>Cerrar</button>@if($faseEsActual && $proyecto->estado_expediente === 'en_tramite')<button class="btn btn-primary" type="submit">Guardar observaciones</button>@endif</div>
                </form>
                @if($faseEsActual && $proyecto->estado_expediente === 'en_tramite' && $fase !== 'memoria_final')
                    <form method="POST" action="{{ route('ups.proyectos.admitirFase', $proyecto->id_proyecto) }}" class="project-phase-admit-form">
                        @csrf <input type="hidden" name="form_key" value="{{ $formKeyFase }}"><input type="hidden" id="admitir-observaciones-{{ $fase }}" name="observaciones" value="{{ old('form_key') === $formKeyFase ? old('observaciones') : '' }}">
                        <button class="btn btn-soft" type="submit">Admitir esta fase y avanzar el expediente</button>
                    </form>
                @elseif($faseEsActual && $proyecto->estado_expediente === 'en_tramite' && $fase === 'memoria_final')
                    <p class="muted project-phase-footnote">Esta fase se podrá admitir cuando la memoria esté aprobada.</p>
                @endif
            </dialog>
        @endforeach

    @if($proyecto->estado_expediente === 'en_tramite')
        <dialog class="project-action-dialog" id="modal-ampliar" aria-labelledby="modal-ampliar-title">
            <form method="POST" enctype="multipart/form-data" action="{{ route('ups.proyectos.ampliar', $proyecto->id_proyecto) }}">
                @csrf <input type="hidden" name="form_key" value="ampliar">
                <div class="project-dialog-head"><h2 id="modal-ampliar-title">Ampliar plazo</h2><button type="button" class="project-dialog-close" data-project-close aria-label="Cerrar">×</button></div>
                <p class="muted">Fecha vigente: <strong>{{ $proyecto->fecha_final?->format('d/m/Y') ?? $proyecto->fecha_inicio?->format('d/m/Y') }}</strong>. La solicitud quedará pendiente de resolución por Jefatura.</p>
                <div class="field"><label for="ampliar-fecha">Nueva fecha</label><input id="ampliar-fecha" name="fecha_nueva" type="date" required min="{{ ($proyecto->fecha_final ?? $proyecto->fecha_inicio)->copy()->addDay()->format('Y-m-d') }}" value="{{ old('form_key') === 'ampliar' ? old('fecha_nueva') : '' }}">@error('fecha_nueva') @if(old('form_key') === 'ampliar')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                <div class="field"><label for="ampliar-motivo">Motivo</label><textarea id="ampliar-motivo" name="motivo" required minlength="10" maxlength="3000" placeholder="Indique el motivo de la ampliación">{{ old('form_key') === 'ampliar' ? old('motivo') : '' }}</textarea>@error('motivo') @if(old('form_key') === 'ampliar')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                <div class="field"><label for="ampliar-respaldo">Documento de respaldo (opcional, PDF hasta 15 MB)</label><input id="ampliar-respaldo" name="respaldo" type="file" accept="application/pdf,.pdf">@error('respaldo') @if(old('form_key') === 'ampliar')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                <div class="form-actions"><button type="button" class="btn" data-project-close>Volver</button><button class="btn btn-primary" type="submit">Solicitar ampliación</button></div>
            </form>
        </dialog>

        @can('prorrogas.resolver')
            <dialog class="project-action-dialog" id="modal-prorroga" aria-labelledby="modal-prorroga-title">
                <form method="POST" action="{{ route('ups.proyectos.prorroga.store', $proyecto->id_proyecto) }}">
                    @csrf <input type="hidden" name="form_key" value="prorroga">
                    <div class="project-dialog-head"><h2 id="modal-prorroga-title">Registrar prórroga</h2><button type="button" class="project-dialog-close" data-project-close aria-label="Cerrar">×</button></div>
                    <p class="muted">Fecha vigente: <strong>{{ $proyecto->fecha_final?->format('d/m/Y') ?? $proyecto->fecha_inicio?->format('d/m/Y') }}</strong></p>
                    <div class="field"><label for="prorroga-fecha">Nueva fecha</label><input id="prorroga-fecha" name="fecha_nueva" type="date" required min="{{ ($proyecto->fecha_final ?? $proyecto->fecha_inicio)->copy()->addDay()->format('Y-m-d') }}" value="{{ old('form_key') === 'prorroga' ? old('fecha_nueva') : '' }}">@error('fecha_nueva') @if(old('form_key') === 'prorroga')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                    <div class="field"><label for="prorroga-autorizacion">Resolución / autorización</label><input id="prorroga-autorizacion" name="autorizacion" required maxlength="255" value="{{ old('form_key') === 'prorroga' ? old('autorizacion') : '' }}" placeholder="Número o referencia de autorización">@error('autorizacion') @if(old('form_key') === 'prorroga')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                    <div class="field"><label for="prorroga-motivo">Motivo (opcional)</label><textarea id="prorroga-motivo" name="motivo" maxlength="3000" placeholder="Escriba el motivo">{{ old('form_key') === 'prorroga' ? old('motivo') : '' }}</textarea>@error('motivo') @if(old('form_key') === 'prorroga')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                    <div class="form-actions"><button type="button" class="btn" data-project-close>Volver</button><button class="btn btn-primary" type="submit">Confirmar prórroga</button></div>
                </form>
            </dialog>
        @endcan

        <dialog class="project-action-dialog" id="modal-retirar" aria-labelledby="modal-retirar-title">
            <form method="POST" action="{{ route('ups.proyectos.retirar', $proyecto->id_proyecto) }}">
                @csrf <input type="hidden" name="form_key" value="retirar">
                <div class="project-dialog-head"><h2 id="modal-retirar-title">Retirar estudiante</h2><button type="button" class="project-dialog-close" data-project-close aria-label="Cerrar">×</button></div>
                <p>Confirme el retiro del estudiante de este proyecto.</p>
                <div class="field"><label for="retirar-estudiante">Estudiante</label><select id="retirar-estudiante" name="estudiante_id" required><option value="">Seleccione un participante</option>@foreach($proyecto->estudiantes as $estudiante)<option value="{{ $estudiante->id_estudiante }}" @selected(old('form_key') === 'retirar' && old('estudiante_id') == $estudiante->id_estudiante)>{{ $estudiante->due }} · {{ $estudiante->nombre_completo }}</option>@endforeach</select>@error('estudiante_id') @if(old('form_key') === 'retirar')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                <div class="field"><label for="retirar-motivo">Motivo obligatorio</label><textarea id="retirar-motivo" name="motivo" required minlength="10" maxlength="3000" placeholder="Escriba el motivo del retiro">{{ old('form_key') === 'retirar' ? old('motivo') : '' }}</textarea>@error('motivo') @if(old('form_key') === 'retirar')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                <div class="form-actions"><button type="button" class="btn" data-project-close>Volver</button><button class="btn btn-primary" type="submit" @disabled($proyecto->estudiantes->isEmpty())>Confirmar retiro</button></div>
            </form>
        </dialog>

        @if(auth()->user()->hasRole('jefe_ups'))
            <dialog class="project-action-dialog" id="modal-cancelar" aria-labelledby="modal-cancelar-title">
                <form method="POST" action="{{ route('ups.proyectos.cancelar', $proyecto->id_proyecto) }}">
                    @csrf <input type="hidden" name="form_key" value="cancelar">
                    <div class="project-dialog-head"><h2 id="modal-cancelar-title">Cancelar proyecto</h2><button type="button" class="project-dialog-close" data-project-close aria-label="Cerrar">×</button></div>
                    <p>Está por cancelar <strong>{{ $proyecto->titulo }}</strong>. Esta acción cerrará el expediente.</p>
                    <div class="field"><label for="cancelar-motivo">Motivo obligatorio</label><textarea id="cancelar-motivo" name="motivo" required minlength="10" maxlength="3000" placeholder="Escriba el motivo de cancelación">{{ old('form_key') === 'cancelar' ? old('motivo') : '' }}</textarea>@error('motivo') @if(old('form_key') === 'cancelar')<small class="project-field-error">{{ $message }}</small>@endif @enderror</div>
                    <div class="form-actions"><button type="button" class="btn" data-project-close>Volver</button><button class="btn btn-primary" type="submit">Confirmar cancelación</button></div>
                </form>
            </dialog>
        @endif

        <style>
            .project-actions-menu{position:relative}.project-actions-menu>summary{display:flex;align-items:center;gap:10px;cursor:pointer;list-style:none}.project-actions-menu>summary::-webkit-details-marker{display:none}.project-actions-menu>summary:after{width:7px;height:7px;border-right:1.5px solid currentColor;border-bottom:1.5px solid currentColor;content:"";transform:rotate(45deg) translateY(-2px)}.project-actions-menu[open]>summary:after{transform:rotate(225deg) translate(-1px,-1px)}
            .project-actions-popover{position:absolute;z-index:30;top:calc(100% + 7px);right:0;min-width:205px;padding:5px;border:1px solid var(--border);border-radius:9px;background:#fff;box-shadow:0 10px 28px #17283a20}.project-actions-popover button{display:block;width:100%;padding:10px;border:0;border-radius:6px;color:#243347;background:transparent;font:inherit;font-size:13px;text-align:left;cursor:pointer}.project-actions-popover button:hover{color:var(--red);background:var(--pale)}.project-actions-popover .project-actions-danger{color:var(--red)}
            .project-action-dialog{width:min(600px,calc(100vw - 28px));max-height:min(88vh,800px);padding:0;border:1px solid var(--border);border-radius:12px;color:#243347;background:#fff;box-shadow:0 24px 70px #17283a40}.project-action-dialog::backdrop{background:#14223580;backdrop-filter:blur(2px)}.project-action-dialog>form{display:grid;gap:16px;padding:22px}.project-dialog-head{display:flex;align-items:center;justify-content:space-between;gap:14px}.project-dialog-head h2{margin:0;color:#233247;font-size:19px}.project-dialog-close{width:32px;height:32px;border:0;border-radius:7px;color:#667386;background:transparent;font-size:25px;line-height:1;cursor:pointer}.project-dialog-close:hover{color:var(--red);background:var(--pale)}.project-action-dialog p{margin:0;color:var(--muted);font-size:13px;line-height:1.5}.project-action-dialog .field{min-width:0}.project-action-dialog .field textarea{min-height:100px}.project-field-error{color:var(--red);font-size:12px}.project-action-dialog .form-actions{display:flex;justify-content:flex-end;gap:9px;margin:0;padding:0 22px 22px}.project-phase-summary,.project-phase-documents{display:grid;gap:10px;padding:14px;border:1px solid var(--border);border-radius:8px}.project-phase-summary span,.project-phase-documents{font-size:12px}.project-phase-documents .btn{justify-self:start}.project-phase-admit-form{padding:0 22px 14px}.project-phase-footnote{padding:0 22px 20px}@media(max-width:650px){.project-action-dialog>form{padding:17px}.project-action-dialog .form-actions{flex-wrap:wrap;padding:0 17px 17px}.project-phase-admit-form{padding:0 17px 14px}.project-phase-footnote{padding:0 17px 17px}.project-actions-popover{right:0}}
        </style>
        <script>
            (() => {
                const menu = document.querySelector('.project-actions-menu');
                document.querySelectorAll('[data-project-modal]').forEach((button) => button.addEventListener('click', () => {
                    menu?.removeAttribute('open');
                    document.getElementById(button.dataset.projectModal)?.showModal();
                }));
                document.querySelectorAll('[data-project-close]').forEach((button) => button.addEventListener('click', () => button.closest('dialog')?.close()));
                document.querySelectorAll('[data-phase-observations]').forEach((textarea) => textarea.addEventListener('input', () => {
                    const target = document.getElementById(`admitir-observaciones-${textarea.dataset.phaseObservations}`);
                    if (target) target.value = textarea.value;
                }));
                document.querySelectorAll('.project-action-dialog').forEach((dialog) => dialog.addEventListener('click', (event) => {
                    if (event.target === dialog) dialog.close();
                }));
                const formKey = @json(old('form_key'));
                if (formKey) {
                    const modalId = formKey.startsWith('fase_') ? `modal-fase-${formKey.slice(5)}` : `modal-${formKey}`;
                    document.getElementById(modalId)?.showModal();
                }
            })();
        </script>
    @endif
@endsection
