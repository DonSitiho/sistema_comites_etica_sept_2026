<?php

namespace App\Http\Requests\Etica;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CargarDocumentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()
            && $this->user()->can(
                'documentos.cargar'
            );
    }

    public function rules(): array
    {
        return [
            'tipo_documento_id' => [
                'required',
                'integer',

                Rule::exists(
                    'tipos_documento',
                    'id'
                )->where(function ($query) {
                    $query
                        ->where(
                            'aplica_sesion',
                            true
                        )
                        ->where(
                            'activo',
                            true
                        );
                }),
            ],

            'archivo' => [
                'required',
                'file',

                /*
                 * Extensión permitida.
                 */
                'mimes:pdf',

                /*
                 * MIME real esperado.
                 */
                'mimetypes:application/pdf',

                /*
                 * Laravel expresa max en KB.
                 *
                 * 10240 KB = 10 MB.
                 */
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_documento_id.required' =>
                'Debe seleccionar el tipo de documento.',

            'tipo_documento_id.exists' =>
                'El tipo de documento seleccionado no es válido.',

            'archivo.required' =>
                'Debe seleccionar un archivo.',

            'archivo.file' =>
                'El elemento seleccionado no es un archivo válido.',

            'archivo.mimes' =>
                'Únicamente se permiten archivos PDF.',

            'archivo.mimetypes' =>
                'El archivo debe ser un PDF válido.',

            'archivo.max' =>
                'El archivo no puede superar los 10 MB.',
        ];
    }
}