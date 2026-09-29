<?php

namespace App\Http\Requests\Etica;

use Illuminate\Foundation\Http\FormRequest;

class ValidarSesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $sesion = $this->route('sesion');

        return $this->user()
            && $sesion
            && $this->user()->can(
                'validar',
                $sesion
            );
    }

    public function rules(): array
    {
        return [];
    }
}