<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#172536">
    <title>Iniciar sesión — SIGPS UPS FMO-UES</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            --navy: #172536;
            --ink: #202a38;
            --muted: #5a6677;
            --maroon: #991b32;
            --border: #dee3ea;
            --canvas: #f7f8fa;
        }

        * { box-sizing: border-box; }
        body { min-width: 320px; min-height: 100vh; margin: 0; color: var(--ink); background: var(--canvas); }
        .login-layout { display: grid; min-height: 100vh; grid-template-columns: minmax(380px, 36.1%) minmax(0, 1fr); }
        .brand-panel {
            position: relative;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            padding: 52px 54px 32px;
            color: white;
            background: var(--navy);
            isolation: isolate;
        }
        .brand-panel::before {
            position: absolute;
            z-index: -2;
            inset: 0;
            background: linear-gradient(180deg, rgba(23, 37, 54, .38), rgba(23, 37, 54, .52)), url('/images/auth/minerva-background.jpg') center / cover;
            content: "";
        }
        .brand-panel::after { position: absolute; z-index: -1; right: -150px; bottom: 13%; width: 470px; height: 470px; border: 1px solid rgba(255,255,255,.08); border-radius: 50%; content: ""; }
        .brand-lockup { display: flex; align-items: center; gap: 14px; }
        .brand-mark { display: block; width: 52px; height: 52px; flex: 0 0 auto; object-fit: contain; }
        .brand-name { margin: 0; font-size: 11px; font-weight: 700; letter-spacing: .13em; line-height: 1.55; }
        .brand-copy { max-width: 400px; margin: auto 0; padding: 90px 0; }
        .eyebrow { margin: 0 0 18px; color: rgba(255,255,255,.78); font-size: 12px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; }
        .brand-copy h1 { max-width: 420px; margin: 0 0 18px; font-family: Georgia, "Times New Roman", serif; font-size: clamp(38px, 4vw, 58px); font-weight: 400; letter-spacing: -.035em; line-height: 1.08; }
        .brand-copy p { max-width: 390px; margin: 0; color: rgba(255,255,255,.76); font-size: 16px; line-height: 1.75; }
        .brand-footer { display: flex; align-items: center; justify-content: space-between; color: rgba(255,255,255,.65); font-size: 11px; }
        .brand-decoration { width: 57px; height: 7px; opacity: .85; }
        .form-panel { display: grid; min-height: 100vh; padding: 48px 32px; place-items: center; }
        .login-card { width: min(100%, 440px); padding: 40px; border: 1px solid rgba(222,227,234,.7); border-radius: 20px; background: #fff; box-shadow: 0 18px 55px rgba(30,43,59,.08); }
        .card-heading { margin-bottom: 30px; }
        .card-heading h2 { margin: 0 0 8px; color: var(--ink); font-size: 27px; font-weight: 650; letter-spacing: -.035em; }
        .card-heading p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.6; }
        .alert { margin-bottom: 20px; padding: 12px 14px; border: 1px solid; border-radius: 9px; font-size: 13px; line-height: 1.5; }
        .alert-error { border-color: #f2b8c0; color: #8a1830; background: #fff3f4; }
        .alert-success { border-color: #b8dfbb; color: #286c31; background: #f1fbf1; }
        .alert ul { margin: 0; padding-left: 18px; }
        .form-group { margin-bottom: 20px; }
        .field-label { display: block; margin-bottom: 8px; color: #303b4a; font-size: 13px; font-weight: 600; }
        .field-input { width: 100%; height: 48px; padding: 0 14px; border: 1px solid var(--border); border-radius: 9px; outline: none; background: white; color: var(--ink); font: inherit; font-size: 14px; transition: border-color .15s, box-shadow .15s; }
        .field-input::placeholder { color: #9aa3af; }
        .field-input:focus { border-color: #a74a5c; box-shadow: 0 0 0 3px rgba(153,27,50,.1); }
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }
        input[type="password"]::-webkit-credentials-auto-fill-button { visibility: hidden; display: none !important; pointer-events: none; }
        .password-wrap { position: relative; }
        .password-wrap .field-input { padding-right: 48px; }
        .password-toggle { position: absolute; top: 0; right: 4px; display: grid; width: 42px; height: 48px; padding: 0; place-items: center; border: 0; background: transparent; cursor: pointer; }
        .password-toggle svg { width: 20px; height: 20px; color: #5a6677; transition: color .15s, transform .15s; }
        .password-toggle:hover svg { color: #28384b; transform: scale(1.08); }
        .password-toggle[aria-pressed="true"] .eye-slash { display: none; }
        .password-toggle:focus-visible { outline: 2px solid var(--maroon); outline-offset: -5px; border-radius: 8px; }
        .form-meta { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: -2px 0 23px; font-size: 12px; }
        .remember { display: inline-flex; align-items: center; gap: 8px; color: var(--muted); cursor: pointer; }
        .remember input { width: 15px; height: 15px; margin: 0; accent-color: var(--maroon); }
        .text-link { color: var(--maroon); font-weight: 600; text-decoration: none; }
        .text-link:hover { text-decoration: underline; }
        .primary-button, .google-button { display: flex; width: 100%; height: 48px; align-items: center; justify-content: center; border-radius: 9px; font: inherit; font-size: 14px; font-weight: 650; }
        .primary-button { border: 1px solid var(--maroon); color: white; background: var(--maroon); cursor: pointer; transition: background .15s, transform .15s; }
        .primary-button:hover { background: #80152a; }
        .primary-button:active { transform: translateY(1px); }
        .divider { display: flex; align-items: center; gap: 14px; margin: 22px 0; color: #8992a0; font-size: 11px; }
        .divider::before, .divider::after { height: 1px; flex: 1; background: var(--border); content: ""; }
        .google-button { gap: 10px; border: 1px solid var(--border); color: #435063; background: white; cursor: not-allowed; opacity: .78; }
        .google-mark { font-size: 17px; font-weight: 700; color: #4285f4; }
        .register-prompt { margin: 24px 0 0; color: var(--muted); font-size: 12px; text-align: center; }
        .register-prompt span { color: #9aa3af; }

        @media (max-width: 760px) {
            .login-layout { grid-template-columns: 1fr; }
            .brand-panel { min-height: 270px; padding: 24px 26px; }
            .brand-lockup { gap: 10px; }
            .brand-mark { width: 44px; height: 44px; }
            .brand-copy { padding: 48px 0 42px; }
            .brand-copy h1 { font-size: 36px; }
            .brand-copy p { font-size: 14px; }
            .brand-footer { display: none; }
            .form-panel { min-height: auto; padding: 28px 18px 40px; }
            .login-card { padding: 30px 24px; }
        }
        @media (max-width: 380px) {
            .form-meta { align-items: flex-start; flex-direction: column; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <main class="login-layout">
        <aside class="brand-panel" aria-label="Unidad de Proyección Social">
            <div class="brand-lockup">
                <img class="brand-mark" src="/images/auth/logo-fmo.jpeg" alt="Logo de la Facultad Multidisciplinaria Oriental">
                <p class="brand-name">UNIVERSIDAD DE EL SALVADOR<br>FACULTAD MULTIDISCIPLINARIA ORIENTAL</p>
            </div>

            <div class="brand-copy">
                <p class="eyebrow">Sistema de Proyección Social</p>
                <h1>Sistema de Proyección Social.</h1>
                <p>Organiza tus proyectos, comparte ideas y avanza con claridad, cada día.</p>
            </div>

            <div class="brand-footer">
                <span>© 2026 Universidad de El Salvador</span>
                <img class="brand-decoration" src="/images/auth/brand-decoration.svg" alt="">
            </div>
        </aside>

        <section class="form-panel" aria-labelledby="login-heading">
            <div class="login-card">
                <header class="card-heading">
                    <h2 id="login-heading">Bienvenido</h2>
                    <p>Ingresa tus datos para continuar.</p>
                </header>

                @if (session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.attempt', [], false) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="field-label" for="login">Correo electrónico</label>
                        <input class="field-input" type="text" id="login" name="login" value="{{ old('login') }}" placeholder="carnet@ues.edu.sv" required autofocus autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label class="field-label" for="password">Contraseña</label>
                        <div class="password-wrap">
                            <input class="field-input" type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required autocomplete="current-password">
                            <button class="password-toggle" type="button" aria-label="Mostrar contraseña" aria-pressed="false" data-password-toggle>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="M2.5 12s3.4-6.5 9.5-6.5 9.5 6.5 9.5 6.5-3.4 6.5-9.5 6.5-9.5-6.5-9.5-6.5Z"></path>
                                    <circle cx="12" cy="12" r="2.6"></circle>
                                    <path class="eye-slash" d="m3 3 18 18"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-meta">
                        <label class="remember" for="remember">
                            <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <span>Recordarme</span>
                        </label>
                        <span class="text-link" title="Recuperación de contraseña aún no configurada">¿Olvidaste tu contraseña?</span>
                    </div>

                    <button class="primary-button" type="submit">Iniciar sesión</button>
                </form>

                <div class="divider" aria-hidden="true">o continúa con</div>
                <button class="google-button" type="button" disabled title="Acceso con Google aún no configurado">
                    <span class="google-mark" aria-hidden="true">G</span>
                    Continuar con Google
                </button>

                <p class="register-prompt"><a href="{{ route('formatos.index') }}">Consultar formatos oficiales de Proyección Social</a></p>
            </div>
        </section>
    </main>
    <script>
        document.querySelector('[data-password-toggle]').addEventListener('click', function () {
            const password = document.getElementById('password');
            const visible = password.type === 'password';
            password.type = visible ? 'text' : 'password';
            this.setAttribute('aria-pressed', String(visible));
            this.setAttribute('aria-label', visible ? 'Ocultar contraseña' : 'Mostrar contraseña');
        });
    </script>
</body>
</html>
