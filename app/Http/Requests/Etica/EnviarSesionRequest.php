<?php

namespace App\Http\Requests\Etica;

use Illuminate\Foundation\Http\FormRequest;

class EnviarSesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $sesion = $this->route('sesion');

        return $this->user()
            && $sesion
            && $this->user()->can(
                'enviar',
                $sesion
            );
    }

    public function rules(): array
    {
        return [];
    }
}