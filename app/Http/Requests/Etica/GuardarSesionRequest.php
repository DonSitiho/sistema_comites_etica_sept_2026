<?php

namespace App\Http\Requests\Etica;

use App\Models\PlanAnual;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarSesionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado
     * para realizar esta petición.
     */
    public function authorize(): bool
    {
        return $this->user()
            && $this->user()->can('sesiones.crear');
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'comite_id' => [
                'required',
                'integer',
                'exists:comites,id',
            ],

            'plan_anual_id' => [
                'required',
                'integer',
                'exists:planes_anuales,id',
            ],

            'ejercicio' => [
                'required',
                'integer',
                'min:2025',
                'max:2100',
            ],

            'trimestre' => [
                'required',
                'integer',
                Rule::in([1, 2, 3, 4]),
            ],

            'fecha_sesion' => [
                'nullable',
                'date',
            ],

            'fecha_limite' => [
                'required',
                'date',
            ],

            'comentarios_enlace' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    /**
     * Validaciones adicionales que involucran
     * más de un campo.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (
                !$this->filled('plan_anual_id') ||
                !$this->filled('comite_id') ||
                !$this->filled('ejercicio')
            ) {
                return;
            }

            $planAnual = PlanAnual::find(
                $this->integer('plan_anual_id')
            );

            if (!$planAnual) {
                return;
            }

            /*
             * El Plan Anual seleccionado debe
             * pertenecer al mismo Comité.
             */
            if (
                (int) $planAnual->comite_id !==
                $this->integer('comite_id')
            ) {
                $validator->errors()->add(
                    'plan_anual_id',
                    'El Plan Anual seleccionado no pertenece al Comité.'
                );
            }

            /*
             * El ejercicio también debe coincidir.
             */
            if (
                (int) $planAnual->ejercicio !==
                $this->integer('ejercicio')
            ) {
                $validator->errors()->add(
                    'ejercicio',
                    'El ejercicio no coincide con el Plan Anual seleccionado.'
                );
            }
        });
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [
            'comite_id.required' =>
                'Debe seleccionar un Comité.',

            'comite_id.exists' =>
                'El Comité seleccionado no existe.',

            'plan_anual_id.required' =>
                'Debe seleccionar un Plan Anual.',

            'plan_anual_id.exists' =>
                'El Plan Anual seleccionado no existe.',

            'ejercicio.required' =>
                'Debe especificar el ejercicio.',

            'trimestre.required' =>
                'Debe seleccionar el trimestre.',

            'trimestre.in' =>
                'El trimestre únicamente puede ser 1, 2, 3 o 4.',

            'fecha_sesion.date' =>
                'La fecha de sesión no tiene un formato válido.',

            'fecha_limite.required' =>
                'Debe indicar la fecha límite.',

            'fecha_limite.date' =>
                'La fecha límite no tiene un formato válido.',

            'comentarios_enlace.max' =>
                'Los comentarios no pueden superar 5000 caracteres.',
        ];
    }
}