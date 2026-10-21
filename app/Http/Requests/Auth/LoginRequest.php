<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required_without_all:email,nombre_usuario', 'string'],
            'email' => ['nullable', 'string'],
            'nombre_usuario' => ['nullable', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Obtener credenciales normalizadas (admite email o nombre de usuario).
     *
     * @return array<string, string>
     */
    public function credentials(): array
    {
        $loginInput = (string) ($this->input('login') ?? $this->input('email') ?? $this->input('nombre_usuario'));

        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'nombre_usuario';

        return [
            $field => $loginInput,
            'password' => (string) $this->input('password'),
        ];
    }

    /**
     * Mensajes de error personalizados.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'login.required_without_all' => 'Debe ingresar su usuario o correo electrónico institucional.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }
}
