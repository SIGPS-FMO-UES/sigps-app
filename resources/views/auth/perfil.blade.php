<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario — SIGPS UPS</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 2rem 1rem; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); padding: 2rem; box-sizing: border-box; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .header h1 { font-size: 1.25rem; margin: 0; color: #111827; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.35rem; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 0.65rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 0.95rem; }
        .section-title { font-size: 1rem; font-weight: 600; margin: 1.5rem 0 0.75rem 0; color: #374151; border-top: 1px solid #f3f4f6; padding-top: 1rem; }
        .btn-primary { padding: 0.75rem 1.5rem; background-color: #1e40af; color: #ffffff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-danger { padding: 0.5rem 1rem; background-color: #dc2626; color: #ffffff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.875rem; }
        .alert-error { background-color: #fee2e2; border: 1px solid #f87171; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .alert-success { background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .user-roles { display: flex; gap: 0.5rem; margin-top: 0.25rem; }
        .badge { background: #e0e7ff; color: #3730a3; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Mi Perfil Institucional</h1>
                <div class="user-roles">
                    @foreach ($user->roles as $role)
                        <span class="badge">{{ strtoupper($role->name) }}</span>
                    @endforeach
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-danger">Cerrar Sesión</button>
            </form>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('perfil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario (Inmutable)</label>
                <input type="text" id="nombre_usuario" value="{{ $user->nombre_usuario }}" disabled style="background-color: #f9fafb; color: #6b7280;">
            </div>

            <div class="form-group">
                <label for="nombre_completo">Nombre Completo</label>
                <input type="text" id="nombre_completo" name="nombre_completo" value="{{ old('nombre_completo', $user->nombre_completo) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="section-title">Cambio de Contraseña (Opcional)</div>

            <div class="form-group">
                <label for="current_password">Contraseña Actual (Requerida solo si desea cambiarla)</label>
                <input type="password" id="current_password" name="current_password" autocomplete="current-password">
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña</label>
                <input type="password" id="password" name="password" autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
            </div>

            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
