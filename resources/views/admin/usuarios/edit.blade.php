@extends('layouts.portal')
@section('title', 'Editar usuario')
@section('heading', 'Usuarios')
@section('subtitle', 'Usuarios / Editar')
@section('page-actions')<a class="btn" href="{{ route('admin.usuarios.index') }}">Regresar</a>@endsection
@section('content')
<form method="POST" action="{{ route('admin.usuarios.update', $usuario->id_usuario) }}">
    @csrf
    @method('PUT')
    <section class="card">
        <div class="card-head"><h2>Editar usuario</h2></div>
        <div class="card-body">
            <div class="grid">
                <div class="field" style="grid-column:1/-1"><label for="nombre_usuario">Nombre de usuario *</label><input id="nombre_usuario" name="nombre_usuario" required maxlength="255" pattern="[A-Za-z0-9_-]+" value="{{ old('nombre_usuario', $usuario->nombre_usuario) }}" autocomplete="username"></div>
                <div class="field" style="grid-column:1/-1"><label for="nombre_completo">Nombre completo *</label><input id="nombre_completo" name="nombre_completo" required maxlength="255" value="{{ old('nombre_completo', $usuario->nombre_completo) }}" autocomplete="name"></div>
                <div class="field"><label for="rol">Rol *</label><select id="rol" name="rol" required><option value="">Seleccione el rol</option><option value="auxiliar_ups" @selected(old('rol', $usuario->getRoleNames()->first()) === 'auxiliar_ups')>Auxiliar UPS</option><option value="jefe_ups" @selected(old('rol', $usuario->getRoleNames()->first()) === 'jefe_ups')>Jefatura UPS</option></select></div>
                <div class="field"><label for="activo">Estado *</label><select id="activo" name="activo" required><option value="1" @selected((string) old('activo', (int) $usuario->activo) === '1')>Activo</option><option value="0" @selected((string) old('activo', (int) $usuario->activo) === '0')>Inactivo</option></select></div>
                <div class="field"><label for="email">Correo electrónico</label><input id="email" name="email" type="email" maxlength="255" value="{{ old('email', $usuario->email) }}" autocomplete="email"></div>
                <div class="field"><label for="password">Nueva contraseña</label><input id="password" name="password" type="password" minlength="10" autocomplete="new-password"><small class="muted">Déjala vacía para conservar la contraseña actual.</small></div>
                <div class="field"><label for="password_confirmation">Confirmar nueva contraseña</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="10" autocomplete="new-password"></div>
            </div>
            <div class="form-actions"><a class="btn" href="{{ route('admin.usuarios.index') }}">Cancelar</a><button class="btn btn-primary" type="submit">Guardar cambios</button></div>
        </div>
    </section>
</form>
@endsection
