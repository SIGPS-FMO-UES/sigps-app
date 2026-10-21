@extends('layouts.portal')
@section('title', 'Nuevo usuario')
@section('heading', 'Usuarios')
@section('subtitle', 'Usuarios / Nuevo')
@section('page-actions')<a class="btn" href="{{ route('admin.usuarios.index') }}">Regresar</a>@endsection
@section('content')
<form method="POST" action="{{ route('admin.usuarios.store') }}">
    @csrf
    <section class="card">
        <div class="card-head"><h2>Registrar usuario</h2></div>
        <div class="card-body">
            <div class="grid">
                <div class="field" style="grid-column:1/-1"><label for="nombre_usuario">Nombre de usuario *</label><input id="nombre_usuario" name="nombre_usuario" required maxlength="255" pattern="[A-Za-z0-9_-]+" value="{{ old('nombre_usuario') }}" autocomplete="username" placeholder="Ingrese el nombre de usuario"></div>
                <div class="field" style="grid-column:1/-1"><label for="nombre_completo">Nombre completo *</label><input id="nombre_completo" name="nombre_completo" required maxlength="255" value="{{ old('nombre_completo') }}" autocomplete="name" placeholder="Ingrese el nombre completo"></div>
                <div class="field"><label for="rol">Rol *</label><select id="rol" name="rol" required><option value="">Seleccione el rol</option><option value="auxiliar_ups" @selected(old('rol') === 'auxiliar_ups')>Auxiliar UPS</option><option value="jefe_ups" @selected(old('rol') === 'jefe_ups')>Jefatura UPS</option></select></div>
                <div class="field"><label for="activo">Estado *</label><select id="activo" name="activo" required><option value="1" @selected(old('activo', '1') === '1')>Activo</option><option value="0" @selected(old('activo') === '0')>Inactivo</option></select></div>
                <div class="field"><label for="email">Correo electrónico</label><input id="email" name="email" type="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" placeholder="correo@ues.edu.sv"></div>
                <div class="field"><label for="password">Contraseña *</label><input id="password" name="password" type="password" required minlength="10" autocomplete="new-password"><small class="muted">Mínimo 10 caracteres.</small></div>
                <div class="field"><label for="password_confirmation">Confirmar contraseña *</label><input id="password_confirmation" name="password_confirmation" type="password" required minlength="10" autocomplete="new-password"></div>
            </div>
            <div class="form-actions"><a class="btn" href="{{ route('admin.usuarios.index') }}">Cancelar</a><button class="btn btn-primary" type="submit">Guardar</button></div>
        </div>
    </section>
</form>
@endsection
