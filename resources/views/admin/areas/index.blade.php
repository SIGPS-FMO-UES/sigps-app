@extends('layouts.portal')
@section('title', 'Áreas de conocimiento')
@section('heading', 'Áreas de conocimiento')
@section('subtitle', 'Administra el catálogo institucional y define cómo se aplican sus cambios.')
@section('content')
<style>
    .areas-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
    .areas-actions{display:flex;align-items:center;gap:7px;flex-wrap:wrap}
    .areas-modal{width:min(500px,calc(100% - 28px));max-width:none;padding:0;border:1px solid var(--border);border-radius:12px;color:#202a38;background:#fff;box-shadow:0 24px 80px #17283a40}
    .areas-modal::backdrop{background:#17283a80;backdrop-filter:blur(2px)}
    .areas-modal-head{padding:19px 22px 14px;border-bottom:1px solid var(--border)}
    .areas-modal-head h2{margin:0;color:#202a38;font-size:17px}
    .areas-modal-head p{margin:6px 0 0;color:var(--muted);font-size:12px;line-height:1.5}
    .areas-modal-body{display:grid;gap:14px;padding:20px 22px}
    .areas-modal-actions{display:flex;justify-content:flex-end;gap:9px;padding:0 22px 20px}
    .areas-icon-button{display:inline-grid;width:32px;height:32px;place-items:center;padding:0;border:1px solid var(--border);border-radius:7px;color:#4d5c6d;background:#fff;cursor:pointer}
    .areas-icon-button:hover{border-color:var(--red);color:var(--red);background:var(--pale)}
    .areas-icon-button svg{width:15px;height:15px}
    .areas-danger{color:#9e2c3e;background:#fff2f3}
    .areas-danger:hover{border-color:#cf5261;color:#9e2c3e;background:#fdebed}
    .areas-status-form{display:inline}
    @media(max-width:650px){.areas-modal-head,.areas-modal-body{padding-inline:16px}.areas-modal-actions{padding-inline:16px}}
</style>

@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-error"><strong>No se pudo completar la operación.</strong><ul style="margin:7px 0 0;padding-left:19px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<div class="notice" style="margin-bottom:16px"><strong>Alcance de cambios y protección de datos</strong>“Proyectos actuales” modifica también los expedientes existentes. “Solo proyectos futuros” conserva el área histórica y crea una nueva versión activa. Si un área ya tiene proyectos vinculados, podrás desactivarla, pero no eliminarla.</div>

<section class="card">
    <div class="card-head areas-toolbar">
        <div><h2>Catálogo institucional <span class="badge">{{ $areas->total() }} áreas</span></h2><p class="muted" style="margin:5px 0 0;font-size:12px">Edita, activa o desactiva cada área sin salir de esta pantalla.</p></div>
        <button class="btn btn-primary" type="button" data-open-modal="create-area-modal">＋ Nueva área</button>
    </div>
    <div class="table-wrap"><table class="table"><thead><tr><th>Área</th><th>Descripción</th><th>Proyectos</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
    @forelse($areas as $area)
        <tr>
            <td><strong>{{ $area->nombre }}</strong></td>
            <td>{{ $area->detalles ?: '—' }}</td>
            <td>{{ $area->proyectos_count }}</td>
            <td><span class="badge {{ $area->activa ? 'badge-success' : '' }}">{{ $area->activa ? 'Activa' : 'Inactiva / histórica' }}</span></td>
            <td><div class="areas-actions">
                <button class="areas-icon-button" type="button" aria-label="Editar {{ $area->nombre }}" title="Editar" data-open-modal="edit-area-modal" data-record-id="{{ $area->id_area_conocimiento }}" data-record-name="{{ $area->nombre }}" data-record-details="{{ $area->detalles }}" data-update-url="{{ route('admin.areas.update', $area->id_area_conocimiento, false) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z"></path></svg></button>
                <form class="areas-status-form" method="POST" action="{{ route('admin.areas.estado', $area->id_area_conocimiento, false) }}">@csrf @method('PATCH')<button class="btn btn-small" type="submit">{{ $area->activa ? 'Desactivar' : 'Activar' }}</button></form>
                <button class="areas-icon-button areas-danger" type="button" aria-label="Eliminar {{ $area->nombre }}" title="{{ $area->proyectos_count > 0 ? 'No se puede eliminar porque tiene proyectos vinculados' : 'Eliminar' }}" @disabled($area->proyectos_count > 0) data-open-modal="delete-area-modal" data-record-name="{{ $area->nombre }}" data-delete-url="{{ route('admin.areas.destroy', $area->id_area_conocimiento, false) }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"></path></svg></button>
            </div></td>
        </tr>
    @empty<tr><td colspan="5" class="empty">No hay áreas registradas. Crea una para comenzar.</td></tr>@endforelse
    </tbody></table></div>
    @if($areas->hasPages())<div class="pagination"><span>{{ $areas->total() }} registros</span>{{ $areas->links() }}</div>@endif
</section>

<dialog class="areas-modal" id="create-area-modal" aria-labelledby="create-area-title">
    <form method="POST" action="{{ route('admin.areas.store', [], false) }}">@csrf
        <div class="areas-modal-head"><h2 id="create-area-title">Nueva área de conocimiento</h2><p>Agrega una categoría para clasificar proyectos de proyección social.</p></div>
        <div class="areas-modal-body"><div class="field"><label for="new-area-name">Nombre</label><input id="new-area-name" name="nombre" maxlength="150" required placeholder="Ej. Educación y alfabetización"></div><div class="field"><label for="new-area-details">Descripción <span class="muted">(opcional)</span></label><textarea id="new-area-details" name="detalles" maxlength="1000" placeholder="Describe brevemente el área"></textarea></div></div>
        <div class="areas-modal-actions"><button class="btn" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit">Crear área</button></div>
    </form>
</dialog>

<dialog class="areas-modal" id="edit-area-modal" aria-labelledby="edit-area-title">
    <form method="POST" id="edit-area-form">@csrf @method('PUT')
        <div class="areas-modal-head"><h2 id="edit-area-title">Editar área de conocimiento</h2><p>Elige cómo aplicar los cambios para mantener el historial de proyectos.</p></div>
        <div class="areas-modal-body"><div class="field"><label for="edit-area-name">Nombre</label><input id="edit-area-name" name="nombre" maxlength="150" required></div><div class="field"><label for="edit-area-details">Descripción <span class="muted">(opcional)</span></label><textarea id="edit-area-details" name="detalles" maxlength="1000"></textarea></div><div class="field"><label for="edit-area-scope">Aplicar cambios a</label><select id="edit-area-scope" name="alcance" required><option value="retroactivo">Proyectos actuales</option><option value="futuro">Solo proyectos futuros</option></select></div></div>
        <div class="areas-modal-actions"><button class="btn" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit">Guardar cambios</button></div>
    </form>
</dialog>

<dialog class="areas-modal" id="delete-area-modal" aria-labelledby="delete-area-title">
    <form method="POST" id="delete-area-form">@csrf @method('DELETE')
        <div class="areas-modal-head"><h2 id="delete-area-title">Confirmar eliminación</h2><p id="delete-area-description">¿Seguro que deseas eliminar esta área?</p></div>
        <div class="areas-modal-body"><div class="notice"><strong>Esta acción no se puede deshacer.</strong>Solo se pueden eliminar áreas sin proyectos vinculados.</div></div>
        <div class="areas-modal-actions"><button class="btn" type="button" data-close-modal>Cancelar</button><button class="btn btn-primary" type="submit">Eliminar área</button></div>
    </form>
</dialog>

<script>
(() => {
    document.querySelectorAll('[data-open-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            const modal = document.getElementById(button.dataset.openModal);
            if (!modal) return;
            if (modal.id === 'edit-area-modal') {
                document.getElementById('edit-area-form').action = button.dataset.updateUrl;
                document.getElementById('edit-area-name').value = button.dataset.recordName;
                document.getElementById('edit-area-details').value = button.dataset.recordDetails;
                document.getElementById('edit-area-scope').value = 'retroactivo';
            }
            if (modal.id === 'delete-area-modal') {
                document.getElementById('delete-area-form').action = button.dataset.deleteUrl;
                document.getElementById('delete-area-description').textContent = `¿Seguro que deseas eliminar el área “${button.dataset.recordName}”?`;
            }
            modal.showModal();
        });
    });
    document.querySelectorAll('[data-close-modal]').forEach((button) => button.addEventListener('click', () => button.closest('dialog')?.close()));
    document.querySelectorAll('.areas-modal').forEach((modal) => modal.addEventListener('click', (event) => {
        if (event.target === modal) modal.close();
    }));
})();
</script>
@endsection
