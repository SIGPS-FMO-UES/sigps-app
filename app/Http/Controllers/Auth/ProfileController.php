<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Muestra la pantalla de edición del perfil del usuario interno.
     */
    public function edit(Request $request): View
    {
        return view('auth.perfil', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Procesa la actualización de datos personales y contraseña.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->nombre_completo = $request->validated('nombre_completo');
        $user->email = $request->validated('email');

        if ($request->filled('password')) {
            $user->password = $request->validated('password');
        }

        $user->save();

        return redirect()->route('perfil.edit')->with('success', 'Perfil institucional actualizado exitosamente.');
    }
}
