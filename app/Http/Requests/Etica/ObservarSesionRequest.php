<?php

namespace App\Http\Requests\Etica;

use Illuminate\Foundation\Http\FormRequest;

class ObservarSesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $sesion = $this->route('sesion');

        return $this->user()
            && $sesion
            && $this->user()->can(
                'observar',
                $sesion
            );
    }

    public function rules(): array
    {
        return [
            'observacion' => [
                'required',
                'string',
                'min:10',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'observacion.required' =>
                'Debe indicar el motivo de la observación.',

            'observacion.min' =>
                'La observación debe contener al menos 10 caracteres.',

            'observacion.max' =>
                'La observación no puede superar 5000 caracteres.',
        ];
    }
}