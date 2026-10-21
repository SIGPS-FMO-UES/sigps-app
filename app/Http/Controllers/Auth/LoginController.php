<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión institucional (Backoffice UPS).
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Procesa la autenticación del personal interno de la UPS.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->credentials();
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('login', 'email', 'nombre_usuario', 'remember'))
                ->withErrors([
                    'login' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
                ]);
        }

        $user = Auth::user();

        // Control de activación de cuenta (RF-COM-01 / HU-03)
        if (! $user->activo) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withInput()->withErrors([
                'login' => 'Su cuenta ha sido desactivada. Comuníquese con la administración técnica de Proyección Social.',
            ]);
        }

        $request->session()->regenerate();

        // Redirección dinámica según el rol del usuario (HU-58)
        if ($user->hasRole('admin')) {
            $destino = Route::has('admin.usuarios.index') ? route('admin.usuarios.index', [], false) : '/admin/usuarios';

            return redirect()->away($destino);
        }

        if ($user->hasAnyRole(['auxiliar_ups', 'jefe_ups'])) {
            $destino = Route::has('ups.dashboard') ? route('ups.dashboard', [], false) : '/ups/dashboard';

            return redirect()->away($destino);
        }

        return redirect()->away('/');
    }

    /**
     * Finaliza la sesión del usuario autenticado.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->away('/login')->with('success', 'Ha cerrado sesión correctamente.');
    }
}
