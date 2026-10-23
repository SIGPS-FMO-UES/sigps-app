@extends('layouts.portal')
@section('title', $modoEdicion ? 'Editar proyecto' : 'Registrar proyecto')
@section('heading', $modoEdicion ? 'Editar proyecto' : 'Apertura de expediente')
@section('subtitle', $modoEdicion ? 'Actualiza los datos administrativos autorizados.' : 'Formulario por pasos para la apertura formal del expediente de proyección social.')
@section('page-actions')
<a class="btn" href="{{ $modoEdicion ? route('ups.proyectos.show', $proyecto->id_proyecto) : route('ups.proyectos.index') }}">← Cancelar</a>
@endsection

@section('content')
<style>
    /* Estilos del asistente por pasos (Wizard) */
    .wizard-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .wizard-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px 24px;
        box-shadow: 0 2px 10px rgba(24, 40, 58, 0.04);
        position: relative;
    }
    .wizard-step {
        display: flex;
        align-items: center;
        gap: 12px;
        background: transparent;
        border: none;
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s ease;
        position: relative;
        z-index: 2;
    }
    .wizard-step:disabled {
        cursor: not-allowed;
        opacity: 0.75;
    }
    .wizard-step:hover:not(:disabled) {
        background: #f8fafc;
    }
    .step-indicator {
        display: grid;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        font-weight: 700;
        font-size: 13px;
        background: #edf1f5;
        color: var(--muted);
        border: 2px solid #dbe2ea;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }
    .wizard-step.active .step-indicator {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
        box-shadow: 0 0 0 4px var(--pale);
    }
    .wizard-step.completed .step-indicator {
        background: var(--navy);
        color: #fff;
        border-color: var(--navy);
    }
    .step-labels {
        display: flex;
        flex-direction: column;
    }
    .step-title {
        font-size: 13px;
        font-weight: 700;
        color: #243347;
    }
    .wizard-step.active .step-title {
        color: var(--red);
    }
    .step-subtitle {
        font-size: 11px;
        color: var(--muted);
    }
    .wizard-step-divider {
        flex: 1;
        height: 2px;
        background: #e2e8f0;
        margin: 0 14px;
        position: relative;
    }
    .wizard-step-divider.completed {
        background: var(--navy);
    }

    /* Paneles del Wizard */
    .wizard-panel {
        display: none;
    }
    .wizard-panel.active {
        display: block;
        animation: fadeInPanel 0.25s ease;
    }
    @keyframes fadeInPanel {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Buscador dinámico de estudiantes */
    .student-search-container {
        position: relative;
        margin-bottom: 20px;
    }
    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .search-input-wrapper input {
        width: 100%;
        padding-left: 38px;
        padding-right: 40px;
        font-size: 13px;
        height: 44px;
        border-radius: 8px;
        border: 1px solid #d0d7e2;
    }
    .search-input-wrapper input:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px var(--pale);
    }
    .search-icon-inside {
        position: absolute;
        left: 12px;
        width: 18px;
        height: 18px;
        color: var(--muted);
        pointer-events: none;
    }
    .search-spinner {
        position: absolute;
        right: 12px;
        width: 18px;
        height: 18px;
        border: 2px solid #e2e8f0;
        border-top-color: var(--red);
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Dropdown de resultados de búsqueda */
    .search-results-dropdown {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 9px;
        box-shadow: 0 10px 28px rgba(23, 40, 58, 0.14);
        max-height: 320px;
        overflow-y: auto;
        z-index: 50;
    }
    .search-result-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 16px;
        border-bottom: 1px solid #edf1f6;
        transition: background 0.15s ease;
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .search-result-item:hover {
        background: #f8fafc;
    }
    .result-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .result-name {
        font-weight: 650;
        color: #1e293b;
        font-size: 13px;
    }
    .result-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        color: var(--muted);
    }
    .result-due-pill {
        display: inline-block;
        padding: 1px 7px;
        background: #edf2f7;
        color: #2b3b4e;
        border-radius: 4px;
        font-weight: 700;
        font-size: 11px;
    }
    .empty-search-message {
        padding: 22px 18px;
        text-align: center;
        color: var(--muted);
        font-size: 13px;
    }

    /* Tabla de estudiantes seleccionados */
    .selected-table-wrap {
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }
    .btn-remove-student {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 9px;
        border: 1px solid #fecdd3;
        border-radius: 6px;
        color: #be123c;
        background: #fff1f2;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-remove-student:hover {
        background: #ffe4e6;
        border-color: #fda4af;
        color: #9f1239;
    }

    /* Modal emergente para registro de nuevo estudiante */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        padding: 16px;
        animation: fadeInOverlay 0.2s ease;
    }
    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .modal-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.24);
        width: 100%;
        max-width: 520px;
        overflow: hidden;
        animation: slideDownModal 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes slideDownModal {
        from { transform: translateY(-16px) scale(0.97); opacity: 0; }
        to { transform: translateY(0) scale(1); opacity: 1; }
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px;
        border-bottom: 1px solid #edf1f6;
        background: #fbfcfd;
    }
    .modal-header h3 {
        margin: 0;
        font-size: 16px;
        color: #1e293b;
    }
    .modal-close-btn {
        background: transparent;
        border: none;
        color: var(--muted);
        font-size: 18px;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        line-height: 1;
    }
    .modal-close-btn:hover {
        color: #1e293b;
        background: #edf2f7;
    }
    .modal-body {
        padding: 22px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 22px;
        background: #f8fafc;
        border-top: 1px solid #edf1f6;
    }

    /* Caja de resumen paso 3 */
    .summary-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 18px 22px;
        margin-bottom: 16px;
    }
    .summary-card h3 {
        margin: 0 0 14px;
        font-size: 14px;
        color: #1e293b;
        border-bottom: 1px solid #edf1f6;
        padding-bottom: 8px;
    }
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px 18px;
    }
    .summary-item small {
        display: block;
        color: var(--muted);
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 3px;
    }
    .summary-item span {
        font-size: 13px;
        color: #1e293b;
        font-weight: 550;
    }

    /* Alerta visual de validación de paso */
    .step-validation-error {
        margin-bottom: 16px;
        padding: 12px 16px;
        border-radius: 8px;
        background: #fef2f2;
        border: 1px solid #fecdd3;
        color: #991b1b;
        font-size: 12px;
        display: none;
    }

    @media (max-width: 768px) {
        .wizard-stepper {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 12px;
        }
        .wizard-step-divider {
            display: none;
        }
    }
</style>

<div class="wizard-container">
    <!-- Stepper Header -->
    <nav class="wizard-stepper" aria-label="Pasos de apertura del expediente">
        <button type="button" class="wizard-step active" id="tab-step-1" onclick="wizard.goToStep(1)">
            <span class="step-indicator" id="ind-step-1">1</span>
            <div class="step-labels">
                <span class="step-title">Información del Proyecto</span>
                <span class="step-subtitle">Datos generales y responsables</span>
            </div>
        </button>
        <div class="wizard-step-divider" id="divider-1-2"></div>
        <button type="button" class="wizard-step" id="tab-step-2" onclick="wizard.goToStep(2)">
            <span class="step-indicator" id="ind-step-2">2</span>
            <div class="step-labels">
                <span class="step-title">Estudiantes Participantes</span>
                <span class="step-subtitle">Búsqueda y registro al vuelo</span>
            </div>
        </button>
        <div class="wizard-step-divider" id="divider-2-3"></div>
        <button type="button" class="wizard-step" id="tab-step-3" onclick="wizard.goToStep(3)">
            <span class="step-indicator" id="ind-step-3">3</span>
            <div class="step-labels">
                <span class="step-title">Revisión y Confirmación</span>
                <span class="step-subtitle">Apertura del expediente</span>
            </div>
        </button>
    </nav>

    <!-- Formulario Principal -->
    <form id="form-proyecto" method="POST" enctype="multipart/form-data" action="{{ $modoEdicion ? route('ups.proyectos.update', $proyecto->id_proyecto) : route('ups.proyectos.store') }}">
        @csrf
        @if($modoEdicion)
            @method('PUT')
        @endif

        <!-- ========================================== -->
        <!-- PASO 1: INFORMACIÓN GENERAL DEL PROYECTO   -->
        <!-- ========================================== -->
        <section class="wizard-panel active" id="panel-step-1">
            <div class="card">
                <div class="card-head">
                    <div>
                        <h2>Paso 1: Información Administrativa del Proyecto</h2>
                        <p class="muted" style="margin:4px 0 0;font-size:12px">Define el correlativo oficial, título, área temática y personal docente asignado.</p>
                    </div>
                    <span class="badge badge-info">Paso 1 de 3</span>
                </div>
                <div class="card-body">
                    <div id="step-1-error" class="step-validation-error">Por favor completa todos los campos requeridos marcados con asterisco (*) antes de continuar.</div>

                    <div class="grid">
                        <div class="field">
                            <label for="numero_proyecto">Número de proyecto (correlativo oficial) *</label>
                            <input type="number" id="numero_proyecto" name="numero_proyecto" required min="1" step="1" inputmode="numeric" placeholder="Ej. 1045" value="{{ old('numero_proyecto', $proyecto->numero_proyecto ?? $siguienteNumero) }}">
                            <small class="muted" style="font-size:11px">Sugerido automáticamente: <strong>#{{ $siguienteNumero }}</strong> (puedes ajustarlo si proviene de un correlativo físico previo).</small>
                            @error('numero_proyecto')
                                <small style="color:var(--danger, #dc2626)">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="titulo">Nombre / Título del proyecto *</label>
                            <input id="titulo" name="titulo" required maxlength="255" placeholder="Ej. Apoyo técnico y pedagógico en escuelas de la zona oriental" value="{{ old('titulo', $proyecto->titulo) }}">
                            @error('titulo')
                                <small style="color:var(--danger, #dc2626)">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="area_de_conocimiento">Área de conocimiento *</label>
                            <select id="area_de_conocimiento" name="area_de_conocimiento" required>
                                <option value="">Selecciona un área temática</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id_area_conocimiento }}" @selected(old('area_de_conocimiento', $proyecto->area_de_conocimiento) == $area->id_area_conocimiento)>
                                        {{ $area->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('area_de_conocimiento')
                                <small style="color:var(--danger, #dc2626)">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="fecha_inicio">Fecha de inicio oficial *</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" required value="{{ old('fecha_inicio', $proyecto->fecha_inicio?->format('Y-m-d') ?? now()->format('Y-m-d')) }}">
                            @error('fecha_inicio')
                                <small style="color:var(--danger, #dc2626)">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="fecha_final">Fecha prevista de finalización (opcional)</label>
                            <input type="date" id="fecha_final" name="fecha_final" value="{{ old('fecha_final', $proyecto->fecha_final?->format('Y-m-d')) }}">
                            @error('fecha_final')
                                <small style="color:var(--danger, #dc2626)">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="tutor">Docente tutor registrado *</label>
                            <input id="tutor" name="tutor" required maxlength="255" value="{{ old('tutor', $proyecto->tutor) }}" placeholder="Nombre y apellido del docente tutor">
                            @error('tutor')
                                <small style="color:var(--danger, #dc2626)">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="subcoordinador">Subcoordinador / Coordinador registrado *</label>
                            <input id="subcoordinador" name="subcoordinador" required maxlength="255" value="{{ old('subcoordinador', $proyecto->subcoordinador) }}" placeholder="Nombre del subcoordinador responsable">
                            @error('subcoordinador')
                                <small style="color:var(--danger, #dc2626)">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="correo_coordinador">Correo del coordinador (opcional)</label>
                            <input id="correo_coordinador" name="correo_coordinador" type="email" maxlength="255" value="{{ old('correo_coordinador', $proyecto->correo_coordinador) }}" placeholder="ejemplo@ues.edu.sv">
                            @error('correo_coordinador')
                                <small style="color:var(--danger, #dc2626)">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="documento">Referencia documental (opcional)</label>
                            <input id="documento" name="documento" maxlength="255" value="{{ old('documento', $proyecto->documento) }}" placeholder="Ej. Oficio UPS-2026-089 o referencia de solicitud">
                        </div>

                        <div class="field" style="grid-column: 1 / -1;">
                            <label for="archivo_documento">Expediente digital en PDF (opcional, máximo 15 MB)</label>
                            <input id="archivo_documento" name="archivo_documento" type="file" accept="application/pdf,.pdf">
                            @if($modoEdicion && $proyecto->documentos->isNotEmpty())
                                <small class="muted" style="margin-top:4px">Este proyecto ya cuenta con <strong>{{ $proyecto->documentos->count() }}</strong> documento(s) adjunto(s). Subir un nuevo archivo agregará una nueva versión o respaldo.</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="margin-top:16px">
                <a class="btn" href="{{ $modoEdicion ? route('ups.proyectos.show', $proyecto->id_proyecto) : route('ups.proyectos.index') }}">Cancelar</a>
                <button type="button" class="btn btn-primary" onclick="wizard.validateAndNext(1)">Continuar a Estudiantes →</button>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- PASO 2: ESTUDIANTES PARTICIPANTES          -->
        <!-- ========================================== -->
        <section class="wizard-panel" id="panel-step-2">
            <div class="card">
                <div class="card-head">
                    <div>
                        <h2>Paso 2: Estudiantes Participantes</h2>
                        <p class="muted" style="margin:4px 0 0;font-size:12px">Busca estudiantes por carnet (DUE) o nombre. Si no existen en el sistema, agrégalos de inmediato sin perder tus datos.</p>
                    </div>
                    <span id="badge-contador-estudiantes" class="badge">0 seleccionados</span>
                </div>
                <div class="card-body">
                    <div id="step-2-error" class="step-validation-error">Debes asignar al menos un (1) estudiante participante para poder continuar.</div>

                    <!-- Barra de Búsqueda y Botón de Registro Rápido -->
                    <div class="student-search-container">
                        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                            <div class="search-input-wrapper" style="flex:1;min-width:280px">
                                <svg class="search-icon-inside" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                                <input type="search" id="input-buscar-estudiante" placeholder="Escribe el carnet (DUE) o nombre para buscar entre más de 1,000 estudiantes..." autocomplete="off">
                                <div id="spinner-busqueda" class="search-spinner" style="display:none"></div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="modalEstudiante.open()" style="white-space:nowrap">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                                <span>Registrar Nuevo Estudiante</span>
                            </button>
                        </div>

                        <!-- Dropdown de resultados en vivo -->
                        <div id="dropdown-resultados" class="search-results-dropdown" style="display:none"></div>
                    </div>

                    <!-- Mensaje de éxito de estudiante agregado al vuelo -->
                    <div id="toast-estudiante-agregado" class="alert" style="display:none;margin-bottom:14px;padding:10px 14px;font-size:12px"></div>

                    <!-- Lista / Tabla de Estudiantes Asignados -->
                    <h3 style="margin:16px 0 10px;font-size:14px;color:#202a38">Estudiantes asignados a este proyecto:</h3>
                    
                    <div class="selected-table-wrap">
                        <table class="table" style="margin:0">
                            <thead>
                                <tr>
                                    <th style="width:140px">Carnet (DUE)</th>
                                    <th>Nombre del Estudiante</th>
                                    <th>Carrera(s)</th>
                                    <th style="width:100px;text-align:right">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-estudiantes-seleccionados">
                                <!-- Filas renderizadas dinámicamente por JavaScript -->
                            </tbody>
                        </table>
                        <div id="empty-state-estudiantes" class="empty" style="display:none">
                            <p style="margin:0 0 6px;font-weight:600;color:#334155">Aún no has agregado ningún estudiante participante.</p>
                            <p class="muted" style="margin:0;font-size:12px">Usa el buscador superior para encontrar un estudiante registrado o haz clic en <strong>"Registrar Nuevo Estudiante"</strong>.</p>
                        </div>
                    </div>

                    <!-- Contenedor de inputs ocultos que se envían con el formulario -->
                    <div id="contenedor-hidden-estudiantes"></div>
                    @error('estudiantes')
                        <div style="color:var(--danger, #dc2626);margin-top:10px;font-size:12px;font-weight:600">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions" style="margin-top:16px">
                <button type="button" class="btn" onclick="wizard.goToStep(1)">← Volver a Datos</button>
                <button type="button" class="btn btn-primary" onclick="wizard.validateAndNext(2)">Continuar a Revisión →</button>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- PASO 3: REVISIÓN Y CONFIRMACIÓN            -->
        <!-- ========================================== -->
        <section class="wizard-panel" id="panel-step-3">
            <div class="card">
                <div class="card-head">
                    <div>
                        <h2>Paso 3: Verificación del Expediente</h2>
                        <p class="muted" style="margin:4px 0 0;font-size:12px">Revisa minuciosamente la información antes de guardar la apertura institucional.</p>
                    </div>
                    <span class="badge badge-success">Paso Final</span>
                </div>
                <div class="card-body">
                    <!-- Resumen del Proyecto -->
                    <div class="summary-card">
                        <h3>Datos Generales del Expediente</h3>
                        <div class="summary-grid">
                            <div class="summary-item">
                                <small>Número Correlativo</small>
                                <span id="resumen-numero">—</span>
                            </div>
                            <div class="summary-item" style="grid-column: span 2">
                                <small>Título del Proyecto</small>
                                <span id="resumen-titulo">—</span>
                            </div>
                            <div class="summary-item">
                                <small>Área de Conocimiento</small>
                                <span id="resumen-area">—</span>
                            </div>
                            <div class="summary-item">
                                <small>Fecha de Inicio</small>
                                <span id="resumen-fecha-inicio">—</span>
                            </div>
                            <div class="summary-item">
                                <small>Fecha Prevista de Finalización</small>
                                <span id="resumen-fecha-final">—</span>
                            </div>
                            <div class="summary-item">
                                <small>Docente Tutor</small>
                                <span id="resumen-tutor">—</span>
                            </div>
                            <div class="summary-item">
                                <small>Subcoordinador / Coordinador</small>
                                <span id="resumen-subcoordinador">—</span>
                            </div>
                            <div class="summary-item">
                                <small>Correo del Coordinador</small>
                                <span id="resumen-correo-coordinador">—</span>
                            </div>
                            <div class="summary-item">
                                <small>Referencia Documental</small>
                                <span id="resumen-documento">—</span>
                            </div>
                            <div class="summary-item">
                                <small>Documento PDF Adjunto</small>
                                <span id="resumen-archivo">—</span>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de Estudiantes -->
                    <div class="summary-card">
                        <h3>Estudiantes que Participarán (<span id="resumen-conteo-estudiantes">0</span>)</h3>
                        <div class="selected-table-wrap">
                            <table class="table" style="margin:0">
                                <thead>
                                    <tr>
                                        <th style="width:140px">Carnet (DUE)</th>
                                        <th>Nombre Completo</th>
                                        <th>Carrera(s)</th>
                                    </tr>
                                </thead>
                                <tbody id="resumen-tbody-estudiantes">
                                    <!-- Renderizado dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="notice">
                        <strong>Aviso Institucional de Apertura de Expediente</strong>
                        Al hacer clic en <em>"{{ $modoEdicion ? 'Guardar Cambios' : 'Confirmar y Abrir Expediente' }}"</em>, el proyecto quedará formalmente registrado en estado <strong>En trámite (Fase Inscripción)</strong> y se vincularán todos los estudiantes listados.
                    </div>
                </div>
            </div>

            <div class="form-actions" style="margin-top:16px">
                <button type="button" class="btn" onclick="wizard.goToStep(2)">← Volver a Estudiantes</button>
                <button type="submit" class="btn btn-primary" id="btn-submit-proyecto">
                    @if($modoEdicion)
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        <span>Guardar Cambios</span>
                    @else
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>Confirmar y Abrir Expediente</span>
                    @endif
                </button>
            </div>
        </section>
    </form>
</div>

<!-- ======================================================== -->
<!-- MODAL: REGISTRAR NUEVO ESTUDIANTE DIRECTAMENTE AL VUELO  -->
<!-- ======================================================== -->
<div id="modal-nuevo-estudiante" class="modal-overlay" style="display:none" role="dialog" aria-modal="true" aria-labelledby="modal-titulo">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="modal-titulo">Registrar Nuevo Estudiante</h3>
            <button type="button" class="modal-close-btn" onclick="modalEstudiante.close()" aria-label="Cerrar ventana">&times;</button>
        </div>
        <form id="form-modal-estudiante" onsubmit="modalEstudiante.submit(event)">
            <div class="modal-body">
                <p class="muted" style="margin:0;font-size:12px;line-height:1.4">
                    Este estudiante se guardará en el catálogo general de estudiantes y se asociará automáticamente a este proyecto.
                </p>

                <div id="modal-alerta-error" class="alert alert-error" style="display:none;margin:0;padding:10px 14px;font-size:12px"></div>

                <div class="field">
                    <label for="modal-due">Carnet / DUE *</label>
                    <input type="text" id="modal-due" required maxlength="50" placeholder="Ej. AA20015" style="text-transform:uppercase">
                    <small class="muted" style="font-size:11px">Código de carnet universitario único del estudiante.</small>
                </div>

                <div class="field">
                    <label for="modal-nombre">Nombre Completo *</label>
                    <input type="text" id="modal-nombre" required maxlength="255" placeholder="Ej. Carlos Roberto Martínez López">
                </div>

                <div class="field">
                    <label for="modal-carrera">Carrera Universitaria *</label>
                    <select id="modal-carrera" required>
                        <option value="">Selecciona la carrera</option>
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera->id_carrera }}">{{ $carrera->nombre }} ({{ number_format($carrera->horas_servicio_social) }} h)</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="modalEstudiante.close()">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btn-modal-guardar">
                    Guardar y Asociar al Proyecto
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- SCRIPT CONTROLADOR DEL WIZARD Y ASIGNACIÓN DE ESTUDIANTES-->
<!-- ======================================================== -->
<script>
(() => {
    // 1. Estado Reactivo de Estudiantes Seleccionados
    const estudiantesSeleccionados = new Map();

    // Inicializar estudiantes desde el servidor (datos precargados o old())
    @foreach($participantesSeleccionados as $est)
        estudiantesSeleccionados.set({{ $est->id_estudiante }}, {
            id_estudiante: {{ $est->id_estudiante }},
            due: @json($est->due),
            nombre_completo: @json($est->nombre_completo),
            carreras: @json($est->carreras->pluck('nombre')->all())
        });
    @endforeach

    // 2. Control del Asistente por Pasos (Wizard)
    let currentStep = 1;
    @if($errors->has('estudiantes') || $errors->has('estudiantes.*'))
        currentStep = 2;
    @endif

    window.wizard = {
        goToStep(step) {
            if (step < 1 || step > 3) return;
            
            // Validar antes de avanzar si se hace click en las pestañas superiores
            if (step > currentStep) {
                if (currentStep === 1 && !this.validarPaso1()) return;
                if (currentStep === 2 && !this.validarPaso2()) return;
            }

            // Ocultar todos los paneles y remover estado activo
            document.querySelectorAll('.wizard-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.wizard-step').forEach(s => s.classList.remove('active'));

            // Activar el panel deseado
            const panel = document.getElementById(`panel-step-${step}`);
            const tab = document.getElementById(`tab-step-${step}`);
            if (panel) panel.classList.add('active');
            if (tab) tab.classList.add('active');

            // Actualizar indicadores visuales de completado
            for (let i = 1; i <= 3; i++) {
                const stepTab = document.getElementById(`tab-step-${i}`);
                const ind = document.getElementById(`ind-step-${i}`);
                if (i < step) {
                    stepTab.classList.add('completed');
                    ind.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                } else {
                    stepTab.classList.remove('completed');
                    ind.innerHTML = String(i);
                }
            }

            // Actualizar divisores
            const div12 = document.getElementById('divider-1-2');
            const div23 = document.getElementById('divider-2-3');
            if (div12) div12.classList.toggle('completed', step > 1);
            if (div23) div23.classList.toggle('completed', step > 2);

            currentStep = step;

            // Si llegamos al Paso 3, actualizar resumen
            if (step === 3) {
                this.actualizarResumenPaso3();
            }

            // Scroll suave hacia la parte superior del formulario
            window.scrollTo({ top: document.querySelector('.wizard-stepper').offsetTop - 20, behavior: 'smooth' });
        },

        validateAndNext(fromStep) {
            if (fromStep === 1) {
                if (!this.validarPaso1()) return;
                this.goToStep(2);
            } else if (fromStep === 2) {
                if (!this.validarPaso2()) return;
                this.goToStep(3);
            }
        },

        validarPaso1() {
            const errBox = document.getElementById('step-1-error');
            const inputs = [
                document.getElementById('numero_proyecto'),
                document.getElementById('titulo'),
                document.getElementById('area_de_conocimiento'),
                document.getElementById('fecha_inicio'),
                document.getElementById('tutor'),
                document.getElementById('subcoordinador')
            ];

            for (const input of inputs) {
                if (input && !input.checkValidity()) {
                    errBox.style.display = 'block';
                    input.reportValidity();
                    input.focus();
                    return false;
                }
            }

            errBox.style.display = 'none';
            return true;
        },

        validarPaso2() {
            const errBox = document.getElementById('step-2-error');
            if (estudiantesSeleccionados.size === 0) {
                errBox.style.display = 'block';
                document.getElementById('input-buscar-estudiante').focus();
                return false;
            }
            errBox.style.display = 'none';
            return true;
        },

        actualizarResumenPaso3() {
            const getVal = id => {
                const el = document.getElementById(id);
                return el ? el.value.trim() : '';
            };
            const getSelectText = id => {
                const el = document.getElementById(id);
                return el && el.selectedIndex >= 0 ? el.options[el.selectedIndex].text : '';
            };

            const num = getVal('numero_proyecto');
            document.getElementById('resumen-numero').textContent = num ? `#${num}` : '—';
            document.getElementById('resumen-titulo').textContent = getVal('titulo') || '—';
            document.getElementById('resumen-area').textContent = getSelectText('area_de_conocimiento') || '—';
            
            const fInicio = getVal('fecha_inicio');
            document.getElementById('resumen-fecha-inicio').textContent = fInicio || '—';
            
            const fFinal = getVal('fecha_final');
            document.getElementById('resumen-fecha-final').textContent = fFinal || 'No definida';

            document.getElementById('resumen-tutor').textContent = getVal('tutor') || '—';
            document.getElementById('resumen-subcoordinador').textContent = getVal('subcoordinador') || '—';
            document.getElementById('resumen-correo-coordinador').textContent = getVal('correo_coordinador') || 'No registrado';
            document.getElementById('resumen-documento').textContent = getVal('documento') || 'Sin referencia';

            const arch = document.getElementById('archivo_documento');
            if (arch && arch.files.length > 0) {
                document.getElementById('resumen-archivo').textContent = `${arch.files[0].name} (${(arch.files[0].size / 1024 / 1024).toFixed(2)} MB)`;
            } else {
                document.getElementById('resumen-archivo').textContent = 'Ninguno seleccionado';
            }

            // Resumen de estudiantes
            document.getElementById('resumen-conteo-estudiantes').textContent = estudiantesSeleccionados.size;
            const tbody = document.getElementById('resumen-tbody-estudiantes');
            tbody.innerHTML = '';
            estudiantesSeleccionados.forEach(est => {
                const tr = document.createElement('tr');
                const carreras = est.carreras && est.carreras.length ? est.carreras.join(', ') : 'Sin carrera registrada';
                tr.innerHTML = `
                    <td><span class="result-due-pill">${escapeHtml(est.due)}</span></td>
                    <td><strong>${escapeHtml(est.nombre_completo)}</strong></td>
                    <td><span class="muted">${escapeHtml(carreras)}</span></td>
                `;
                tbody.appendChild(tr);
            });
        }
    };

    // 3. Renderizado de la Tabla de Estudiantes Asignados (Paso 2)
    function renderEstudiantes() {
        const tbody = document.getElementById('tbody-estudiantes-seleccionados');
        const emptyState = document.getElementById('empty-state-estudiantes');
        const hiddenContainer = document.getElementById('contenedor-hidden-estudiantes');
        const badgeContador = document.getElementById('badge-contador-estudiantes');

        tbody.innerHTML = '';
        hiddenContainer.innerHTML = '';

        const total = estudiantesSeleccionados.size;
        badgeContador.textContent = `${total} ${total === 1 ? 'seleccionado' : 'seleccionados'}`;
        badgeContador.className = `badge ${total > 0 ? 'badge-success' : ''}`;

        if (total === 0) {
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';

        estudiantesSeleccionados.forEach(est => {
            // Hidden input para envío de formulario
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'estudiantes[]';
            input.value = est.id_estudiante;
            hiddenContainer.appendChild(input);

            // Fila de tabla
            const tr = document.createElement('tr');
            const carreras = est.carreras && est.carreras.length ? est.carreras.join(', ') : 'Sin carrera registrada';
            tr.innerHTML = `
                <td><span class="result-due-pill">${escapeHtml(est.due)}</span></td>
                <td><strong>${escapeHtml(est.nombre_completo)}</strong></td>
                <td><span class="muted" style="font-size:11px">${escapeHtml(carreras)}</span></td>
                <td style="text-align:right">
                    <button type="button" class="btn-remove-student" onclick="removerEstudiante(${est.id_estudiante})">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                        <span>Quitar</span>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    window.removerEstudiante = function(id) {
        estudiantesSeleccionados.delete(Number(id));
        renderEstudiantes();
    };

    window.agregarEstudiante = function(est) {
        estudiantesSeleccionados.set(Number(est.id_estudiante), {
            id_estudiante: Number(est.id_estudiante),
            due: est.due,
            nombre_completo: est.nombre_completo,
            carreras: est.carreras || []
        });
        renderEstudiantes();
        
        // Cerrar dropdown de búsqueda
        const dropdown = document.getElementById('dropdown-resultados');
        if (dropdown) dropdown.style.display = 'none';

        // Limpiar input de búsqueda
        const searchInput = document.getElementById('input-buscar-estudiante');
        if (searchInput) searchInput.value = '';

        // Toast de confirmación
        mostrarToast(`Estudiante ${est.due} (${est.nombre_completo}) agregado al proyecto.`);
    };

    function mostrarToast(msg) {
        const toast = document.getElementById('toast-estudiante-agregado');
        if (!toast) return;
        toast.textContent = msg;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 4000);
    }

    // 4. Búsqueda Asíncrona (AJAX con debounce)
    let searchTimeout = null;
    const searchInput = document.getElementById('input-buscar-estudiante');
    const dropdown = document.getElementById('dropdown-resultados');
    const spinner = document.getElementById('spinner-busqueda');

    if (searchInput) {
        searchInput.addEventListener('input', e => {
            const query = e.target.value.trim();
            clearTimeout(searchTimeout);

            if (query.length < 2) {
                dropdown.style.display = 'none';
                dropdown.innerHTML = '';
                return;
            }

            spinner.style.display = 'block';

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('ups.estudiantes.buscar') }}?q=${encodeURIComponent(query)}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(estudiantes => {
                    spinner.style.display = 'none';
                    renderDropdownResultados(estudiantes, query);
                })
                .catch(err => {
                    spinner.style.display = 'none';
                    console.error('Error buscando estudiantes:', err);
                });
            }, 250);
        });

        // Prevenir envío del formulario al presionar Enter en el campo de búsqueda
        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        // Cerrar dropdown al hacer click afuera
        document.addEventListener('click', e => {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    }

    function renderDropdownResultados(resultados, query) {
        dropdown.innerHTML = '';

        if (!resultados || resultados.length === 0) {
            dropdown.innerHTML = `
                <div class="empty-search-message">
                    No se encontró ningún estudiante con <strong>"${escapeHtml(query)}"</strong>.<br>
                    <button type="button" class="btn btn-primary btn-small" style="margin-top:10px" onclick="modalEstudiante.openWithQuery('${escapeHtml(query)}')">
                        Registrar "${escapeHtml(query)}" como nuevo estudiante
                    </button>
                </div>
            `;
            dropdown.style.display = 'block';
            return;
        }

        resultados.forEach(est => {
            const estaAgregado = estudiantesSeleccionados.has(Number(est.id_estudiante));
            const item = document.createElement('div');
            item.className = 'search-result-item';

            const carreras = est.carreras && est.carreras.length ? est.carreras.join(', ') : 'Sin carrera';

            item.innerHTML = `
                <div class="result-info">
                    <span class="result-name">${escapeHtml(est.nombre_completo)}</span>
                    <div class="result-meta">
                        <span class="result-due-pill">${escapeHtml(est.due)}</span>
                        <span>${escapeHtml(carreras)}</span>
                    </div>
                </div>
                <div>
                    ${estaAgregado 
                        ? '<span class="badge badge-success" style="font-size:11px">Ya agregado</span>' 
                        : `<button type="button" class="btn btn-small btn-primary" onclick='agregarEstudiante(${JSON.stringify(est)})'>Agregar</button>`}
                </div>
            `;
            dropdown.appendChild(item);
        });

        dropdown.style.display = 'block';
    }

    // 5. Modal de Registro Rápido de Nuevo Estudiante
    const modalEl = document.getElementById('modal-nuevo-estudiante');
    const modalForm = document.getElementById('form-modal-estudiante');
    const modalDueInput = document.getElementById('modal-due');
    const modalNombreInput = document.getElementById('modal-nombre');
    const modalCarreraSelect = document.getElementById('modal-carrera');
    const modalErrorBox = document.getElementById('modal-alerta-error');
    const modalBtnGuardar = document.getElementById('btn-modal-guardar');

    window.modalEstudiante = {
        open() {
            modalErrorBox.style.display = 'none';
            modalForm.reset();
            modalEl.style.display = 'flex';
            setTimeout(() => modalDueInput.focus(), 60);
        },
        openWithQuery(query) {
            this.open();
            // Si la consulta parece carnet (contiene números o formato habitual)
            if (/^[a-zA-Z]{1,4}\d{2,}/.test(query) || /^\d+$/.test(query)) {
                modalDueInput.value = query.toUpperCase();
                modalNombreInput.focus();
            } else {
                modalNombreInput.value = query;
                modalDueInput.focus();
            }
        },
        close() {
            modalEl.style.display = 'none';
        },
        submit(e) {
            e.preventDefault();
            modalErrorBox.style.display = 'none';

            const due = modalDueInput.value.trim().toUpperCase();
            const nombre = modalNombreInput.value.trim();
            const carreraId = modalCarreraSelect.value;

            if (!due || !nombre) {
                modalErrorBox.textContent = 'Por favor completa el carnet (DUE) y el nombre completo.';
                modalErrorBox.style.display = 'block';
                return;
            }

            modalBtnGuardar.disabled = true;
            modalBtnGuardar.textContent = 'Guardando estudiante...';

            const payload = {
                due: due,
                nombre_completo: nombre,
                carreras: carreraId ? [parseInt(carreraId, 10)] : []
            };

            fetch(`{{ route('ups.estudiantes.storeRapido') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) {
                    throw data;
                }
                return data;
            })
            .then(res => {
                modalBtnGuardar.disabled = false;
                modalBtnGuardar.textContent = 'Guardar y Asociar al Proyecto';
                
                // Agregar al listado del proyecto
                if (res.estudiante) {
                    agregarEstudiante(res.estudiante);
                }

                modalEstudiante.close();
            })
            .catch(err => {
                modalBtnGuardar.disabled = false;
                modalBtnGuardar.textContent = 'Guardar y Asociar al Proyecto';
                
                let msg = 'Ocurrió un error al registrar el estudiante.';
                if (err.errors) {
                    msg = Object.values(err.errors).flat().join(' ');
                } else if (err.message) {
                    msg = err.message;
                }
                modalErrorBox.textContent = msg;
                modalErrorBox.style.display = 'block';
            });
        }
    };

    // Cerrar modal al presionar ESC o al hacer clic en el backdrop
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && modalEl.style.display === 'flex') {
            modalEstudiante.close();
        }
    });
    modalEl.addEventListener('click', e => {
        if (e.target === modalEl) {
            modalEstudiante.close();
        }
    });

    // Utilidad para escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Inicialización al cargar la página
    renderEstudiantes();
    wizard.goToStep(currentStep);
})();
</script>
@endsection
