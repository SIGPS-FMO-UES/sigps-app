<?php

namespace App\Http\Requests\Ups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class DictamenMemoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['auxiliar_ups', 'jefe_ups']) ?? false;
    }

    public function rules(): array
    {
        return [
            'resultado' => ['required', Rule::in(['aprobado', 'observado'])],
            'sellos_verificados' => ['required', 'boolean'],
            'tinta_azul_verificada' => ['required', 'boolean'],
            'observaciones' => [
                Rule::requiredIf($this->input('resultado') === 'observado'),
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (
                    $this->input('resultado') === 'aprobado'
                    && (! $this->boolean('sellos_verificados') || ! $this->boolean('tinta_azul_verificada'))
                ) {
                    $validator->errors()->add('resultado', 'Para aprobar la memoria se deben verificar los sellos y la tinta azul.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'observaciones.required' => 'Indica el motivo de la observación.',
        ];
    }
}
