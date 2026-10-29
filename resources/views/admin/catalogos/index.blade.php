@extends('layouts.portal')
@section('title', 'Carreras y departamentos')
@section('heading', 'Carreras y departamentos')
@section('subtitle', 'Organiza las carreras académicas y sus departamentos. Los cambios se reflejan en el registro de estudiantes.')
@section('content')
<style>
    .catalog-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
    .catalog-actions{display:flex;gap:8px;flex-wrap:wrap}
    .catalog-modal{width:min(480px,calc(100% - 28px));max-width:none;padding:0;border:1px solid var(--border);border-radius:12px;color:#202a38;background:#fff;box-shadow:0 24px 80px #17283a40}
    .catalog-modal::backdrop{background:#17283a80;backdrop-filter:blur(2px)}
    .catalog-modal-head{padding:19px 22px 14px;border-bottom:1px solid var(--border)}
    .catalog-modal-head h2{margin:0;color:#202a38;font-size:17px}
    .catalog-modal-head p{margin:6px 0 0;color:var(--muted);font-size:12px;line-height:1.5}
    .catalog-modal-body{display:grid;gap:14px;padding:20px 22px}
    .catalog-modal-actions{display:flex;justify-content:flex-end;gap:9px;padding:0 22px 20px}
    .catalog-icon-button{display:inline-grid;width:32px;height:32px;place-items:center;padding:0;border:1px solid var(--border);border-radius:7px;color:#4d5c6d;background:#fff;cursor:pointer}
    .catalog-icon-button:hover{border-color:var(--red);color:var(--red);background:var(--pale)}
    .catalog-icon-button svg{width:15px;height:15px}
    .catalog-danger{color:#9e2c3e;background:#fff2f3}
    .catalog-danger:hover{border-color:#cf5261;color:#9e2c3e;background:#fdebed}
    .catalog-count{margin-left:6px}
    @media(max-width:650px){.catalog-modal-head,.catalog-modal-body{padding-inline:16px}.catalog-modal-actions{padding-inline:16px}}
</style>

@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-error"><strong>No se pudo completar la operación.</strong><ul style="margin:7px 0 0;padding-left:19px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="notice" style="margin-bottom:16px"><strong>Protección de datos vinculados</strong>Para evitar perder información, primero reasigna o elimina las carreras de un departamento; tampoco se puede eliminar una carrera que tenga estudiantes inscritos.</div>

<section class="card">
    <div class="card-head catalog-toolbar">
        <div><h2>Departamentos <span class="badge catalog-count">{{ $departamentos->total() }}</span></h2><p class="muted" style="margin:5px 0 0;font-size:12px">Cada departamento puede agrupar varias carreras.</p></div>
        <button class="btn btn-primary" type="button" data-open-modal="create-department-modal">＋ Nuevo departamento</button>
    </div>
    <div class="table-wrap"><table class="table"><thead><tr><th>Departamento</th><th>Carreras</th><th>Acciones</th></tr></thead><tbody>
        @forelse($departamentos as $departamento)
            <tr>
                <td><strong>{{ $departamento->nombre }}</strong></td><td>{{ $departamento->carreras_count }}</td>
                <td><div class="catalog-actions">
                    <button class="catalog-icon-button" type="button" aria-label="Editar {{ $departamento->nombre }}" title="Editar" data-open-modal="edit-department-modal" data-record-id="{{ $departamento->id_departamento }}" data-record-name="{{ $departamento->nombre }}" data-update-url="{{ route('admin.departamentos.update', $departamento->id_departamento, false) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z"></path></svg></button>
                    <button class="catalog-icon-button catalog-danger" type="button" aria-label="Eliminar {{ $departamento->nombre }}" title="Eliminar" @disabled($departamento->carreras_count > 0) data-open-modal="delete-modal" data-record-name="el departamento {{ $departamento->nombre }}" data-delete-url="{{ route('admin.departamentos.destroy', $departamento->id_departamento, false) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"></path></svg></button>
                </div></td>
            </tr>
        @empty<tr><td colspan="3" class="empty">Aún no hay departamentos. Crea uno para comenzar el catálogo.</td></tr>@endforelse
    </tbody></table></div>
    @if($departamentos->hasPages())
        <div class="pagination">
            <span>Mostrando {{ $departamentos->firstItem() }}–{{ $departamentos->lastItem() }} de {{ $departamentos->total() }}</span>
            {{ $departamentos->onEachSide(1)->links() }}
        </div>
    @endif
</section>

<section class="card" style="margin-top:16px">
    <div class="card-head catalog-toolbar">
        <div><h2>Carreras <span class="badge catalog-count">{{ $carreras->total() }}</span></h2><p class="muted" style="margin:5px 0 0;font-size:12px">Las carreras se asignan a un departamento.</p></div>
        <button class="btn btn-primary" type="button" data-open-modal="create-career-modal" @disabled(($todosLosDepartamentos ?? $departamentos)->isEmpty())>＋ Nueva carrera</button>
    </div>
    <div class="table-wrap"><table class="table"><thead><tr><th>Código</th><th>Carrera</th><th>Departamento</th><th>Horas de servicio</th><th>Estudiantes inscritos</th><th>Acciones</th></tr></thead><tbody>
        @forelse($carreras as $carrera)
            <tr>
                <td><span class="badge" style="background:#f1f5f9;color:#334155;font-weight:700">{{ $carrera->codigo ?: '—' }}</span></td>
                <td><strong>{{ $carrera->nombre }}</strong></td><td>{{ $carrera->departamentoRel?->nombre ?? 'Sin departamento' }}</td><td><span class="badge" style="background:#eaf2fd;color:#1e40af;font-weight:700">{{ number_format($carrera->horas_servicio_social) }} h</span></td><td>{{ $carrera->estudiantes_count }}</td>
                <td><div class="catalog-actions">
                    <button class="catalog-icon-button" type="button" aria-label="Editar {{ $carrera->nombre }}" title="Editar" data-open-modal="edit-career-modal" data-record-id="{{ $carrera->id_carrera }}" data-record-code="{{ $carrera->codigo }}" data-record-name="{{ $carrera->nombre }}" data-department-id="{{ $carrera->departamento }}" data-hours="{{ $carrera->horas_servicio_social }}" data-update-url="{{ route('admin.carreras.update', $carrera->id_carrera, false) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z"></path></svg></button>
                    <button class="catalog-icon-button catalog-danger" type="button" aria-label="Eliminar {{ $carrera->nombre }}" title="Eliminar" @disabled($carrera->estudiantes_count > 0) data-open-modal="delete-modal" data-record-name="la carrera {{ $carrera->nombre }}" data-delete-url="{{ route('admin.carreras.destroy', $carrera->id_carrera, false) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"></path></svg></button>
                </div></td>
            </tr>
        @empty<tr><td colspan="6" class="empty">Aún no hay carreras registradas.</td></tr>@endforelse
    </tbody></table></div>
    @if($carreras->hasPages())
        <div class="pagination">
            <span>Mostrando {{ $carreras->firstItem() }}–{{ $carreras->lastItem() }} de {{ $carreras->total() }}</span>
            {{ $carreras->onEachSide(1)->links() }}
        </div>
    @endif
</section>

<dialog class="catalog-modal" id="create-department-modal" aria-labelledby="create-department-title">
    <form method="POST" action="{{ route('admin.departamentos.store', [], false) }}">@csrf
        <div class="catalog-modal-head"><h2 id="create-department-title">Nuevo departamento</h2><p>Registra un departamento académico para organizar sus carreras.</p></div>
        <div class="catalog-modal-body"><div class="field"><label for="new-department-name">Nombre del departamento</label><input id="new-department-name" name="nombre" maxlength="150" required placeholder="Ej. Ingeniería y Arquitectura"></div></div>
        <div class="catalog-modal-actions"><button class="btn" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit">Crear departamento</button></div>
    </form>
</dialog>

<dialog class="catalog-modal" id="create-career-modal" aria-labelledby="create-career-title">
    <form method="POST" action="{{ route('admin.carreras.store', [], false) }}">@csrf
        <div class="catalog-modal-head"><h2 id="create-career-title">Nueva carrera</h2><p>Indica el código, nombre, departamento y las horas de servicio social requeridas.</p></div>
        <div class="catalog-modal-body"><div class="field"><label for="new-career-code">Código de la carrera *</label><input id="new-career-code" name="codigo" maxlength="50" required value="{{ old('codigo') }}" placeholder="Ej. I10515" style="text-transform:uppercase"><small class="muted" style="font-size:11px;margin-top:2px">Código alfanumérico institucional (ej. I10515, L10501).</small></div><div class="field"><label for="new-career-name">Nombre de la carrera *</label><input id="new-career-name" name="nombre" maxlength="150" required placeholder="Ej. Ingeniería de Sistemas"></div><div class="field"><label for="new-career-department">Departamento *</label><select id="new-career-department" name="departamento" required><option value="">Selecciona un departamento</option>@foreach(($todosLosDepartamentos ?? $departamentos) as $departamento)<option value="{{ $departamento->id_departamento }}">{{ $departamento->nombre }}</option>@endforeach</select></div><div class="field"><label for="new-career-hours">Horas requeridas de servicio social *</label><input id="new-career-hours" name="horas_servicio_social" type="number" min="1" max="5000" required value="{{ old('horas_servicio_social', 500) }}" placeholder="Ej. 500"><small class="muted" style="font-size:11px;margin-top:2px">Horas requeridas para que un estudiante complete el servicio social en esta carrera.</small></div></div>
        <div class="catalog-modal-actions"><button class="btn" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit">Crear carrera</button></div>
    </form>
</dialog>

<dialog class="catalog-modal" id="edit-department-modal" aria-labelledby="edit-department-title">
    <form method="POST" id="edit-department-form">@csrf @method('PUT')
        <div class="catalog-modal-head"><h2 id="edit-department-title">Editar departamento</h2><p>Actualiza el nombre. Las carreras vinculadas conservarán su relación.</p></div>
        <div class="catalog-modal-body"><div class="field"><label for="edit-department-name">Nombre del departamento</label><input id="edit-department-name" name="nombre" maxlength="150" required></div></div>
        <div class="catalog-modal-actions"><button class="btn" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit">Guardar cambios</button></div>
    </form>
</dialog>

<dialog class="catalog-modal" id="edit-career-modal" aria-labelledby="edit-career-title">
    <form method="POST" id="edit-career-form">@csrf @method('PUT')
        <div class="catalog-modal-head"><h2 id="edit-career-title">Editar carrera</h2><p>Puedes actualizar su código, nombre, departamento o las horas necesarias.</p></div>
        <div class="catalog-modal-body"><div class="field"><label for="edit-career-code">Código de la carrera *</label><input id="edit-career-code" name="codigo" maxlength="50" required placeholder="Ej. I10515" style="text-transform:uppercase"><small class="muted" style="font-size:11px;margin-top:2px">Código alfanumérico institucional (ej. I10515, L10501).</small></div><div class="field"><label for="edit-career-name">Nombre de la carrera *</label><input id="edit-career-name" name="nombre" maxlength="150" required></div><div class="field"><label for="edit-career-department">Departamento *</label><select id="edit-career-department" name="departamento" required>@foreach(($todosLosDepartamentos ?? $departamentos) as $departamento)<option value="{{ $departamento->id_departamento }}">{{ $departamento->nombre }}</option>@endforeach</select></div><div class="field"><label for="edit-career-hours">Horas requeridas de servicio social *</label><input id="edit-career-hours" name="horas_servicio_social" type="number" min="1" max="5000" required placeholder="Ej. 500"><small class="muted" style="font-size:11px;margin-top:2px">Horas requeridas para que un estudiante complete el servicio social en esta carrera.</small></div></div>
        <div class="catalog-modal-actions"><button class="btn" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit">Guardar cambios</button></div>
    </form>
</dialog>

<dialog class="catalog-modal" id="delete-modal" aria-labelledby="delete-title">
    <form method="POST" id="delete-form">@csrf @method('DELETE')
        <div class="catalog-modal-head"><h2 id="delete-title">Confirmar eliminación</h2><p id="delete-description">¿Seguro que deseas eliminar este registro?</p></div>
        <div class="catalog-modal-body"><div class="notice"><strong>Esta acción no se puede deshacer.</strong>Los registros con estudiantes o carreras asociadas no se pueden eliminar.</div></div>
        <div class="catalog-modal-actions"><button class="btn" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit" style="background:#991b32">Eliminar</button></div>
    </form>
</dialog>

<script>
(() => {
    document.querySelectorAll('[data-open-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            const modal = document.getElementById(button.dataset.openModal);
            if (!modal) return;
            if (modal.id === 'edit-department-modal') {
                document.getElementById('edit-department-form').action = button.dataset.updateUrl;
                document.getElementById('edit-department-name').value = button.dataset.recordName;
            }
            if (modal.id === 'edit-career-modal') {
                document.getElementById('edit-career-form').action = button.dataset.updateUrl;
                document.getElementById('edit-career-code').value = button.dataset.recordCode || '';
                document.getElementById('edit-career-name').value = button.dataset.recordName;
                document.getElementById('edit-career-department').value = button.dataset.departmentId;
                document.getElementById('edit-career-hours').value = button.dataset.hours || 500;
            }
            if (modal.id === 'delete-modal') {
                document.getElementById('delete-form').action = button.dataset.deleteUrl;
                document.getElementById('delete-description').textContent = `¿Seguro que deseas eliminar ${button.dataset.recordName}?`;
            }
            modal.showModal();
        });
    });
    document.querySelectorAll('[data-close-modal]').forEach((button) => button.addEventListener('click', () => button.closest('dialog')?.close()));
    document.querySelectorAll('.catalog-modal').forEach((modal) => modal.addEventListener('click', (event) => {
        if (event.target === modal) modal.close();
    }));
})();
</script>
@endsection
