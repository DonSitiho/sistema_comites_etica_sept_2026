<?php

namespace App\Http\Requests\Etica;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarSesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $sesion = $this->route('sesion');

        return $this->user()
            && $sesion
            && $this->user()->can(
                'update',
                $sesion
            );
    }

    public function rules(): array
    {
        $sesion = $this->route('sesion');

        $reglas = [
            'comentarios_enlace' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];

        /*
         * Si la fecha todavía no ha sido bloqueada,
         * puede modificarse.
         */
        if (!$sesion->fechaEstaBloqueada()) {
            $reglas['fecha_sesion'] = [
                'required',
                'date',
            ];
        } else {

            /*
             * Si ya fue enviada anteriormente,
             * Laravel rechazará cualquier intento
             * de modificar fecha_sesion.
             */
            $reglas['fecha_sesion'] = [
                'prohibited',
            ];
        }

        return $reglas;
    }

    public function messages(): array
    {
        return [
            'fecha_sesion.required' =>
                'Debe indicar la fecha de la sesión.',

            'fecha_sesion.date' =>
                'La fecha de sesión no es válida.',

            'fecha_sesion.prohibited' =>
                'La fecha de la sesión ya fue bloqueada y no puede modificarse.',

            'comentarios_enlace.max' =>
                'Los comentarios no pueden superar 5000 caracteres.',
        ];
    }
}