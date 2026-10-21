<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — SIGPS UPS FMO-UES</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); width: 100%; max-width: 400px; padding: 2rem; box-sizing: border-box; }
        .header { text-align: center; margin-bottom: 1.5rem; }
        .header h1 { font-size: 1.25rem; margin: 0 0 0.5rem 0; color: #111827; }
        .header p { font-size: 0.875rem; color: #6b7280; margin: 0; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.35rem; }
        input[type="text"], input[type="password"] { width: 100%; padding: 0.65rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; font-size: 0.95rem; }
        .checkbox-group { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; margin-bottom: 1.25rem; }
        button { width: 100%; padding: 0.75rem; background-color: #1e40af; color: #ffffff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.95rem; }
        button:hover { background-color: #1d4ed8; }
        .alert-error { background-color: #fee2e2; border: 1px solid #f87171; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .alert-success { background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>SIGPS — Backoffice UPS</h1>
            <p>Unidad de Proyección Social (FMO-UES)</p>
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

        <form action="{{ route('login.attempt') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="login">Usuario o Correo Institucional</label>
                <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" style="margin-bottom: 0;">Recordar sesión</label>
            </div>

            <button type="submit">Ingresar al Sistema</button>
        </form>
    </div>
</body>
</html>
