<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RecepcionExpedienteAvaladoRequest extends FormRequest
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
            'carrera_id' => ['required', 'integer', 'exists:carreras,id_carrera'],
            'proyecto_id' => ['required', 'integer', 'exists:proyectos,id_proyecto'],
            'fase' => ['required', 'string', 'in:inscripcion,anteproyecto,avance_50,memoria_final'],
            'codigo_aval_carrera' => ['required', 'string', 'max:100'],
            'coordinador_firmante' => ['required', 'string', 'max:255'],
            'fecha_emision_aval' => ['required', 'date'],
            'archivo_pdf_expediente' => ['required', 'file', 'mimes:pdf', 'max:25600'],
            'archivo_hash_sha256' => ['required', 'string', 'size:64'],
        ];
    }

    /**
     * Mensajes de error personalizados para la API.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'carrera_id.exists' => 'La carrera especificada no existe en los registros institucionales.',
            'proyecto_id.exists' => 'El proyecto especificado no existe.',
            'fase.in' => 'La fase debe ser: inscripcion, anteproyecto, avance_50 o memoria_final.',
            'archivo_pdf_expediente.mimes' => 'El archivo debe ser un documento PDF legible.',
            'archivo_pdf_expediente.max' => 'El archivo PDF no puede exceder los 25 MB.',
            'archivo_hash_sha256.size' => 'El hash SHA-256 debe tener una longitud exacta de 64 caracteres hexadecimales.',
        ];
    }
}
