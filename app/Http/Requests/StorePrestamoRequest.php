<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrestamoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha' => ['required', 'date', 'after_or_equal:' . now()->toDateString()],
            'valor_prestado' => ['required', 'numeric'],
            'cuotas' => ['required', 'integer','min:1', 'max:5'],
            'porcentaje' => ['required', 'integer', 'min:7', 'max:7'],
            'producto_id' => ['required', 'exists:productos,id'],
            'cliente_id' => ['required', 'exists:clientes,id']
        ];
    }

    public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
            'fecha.after_or_equal' => 'La fecha debe ser igual o posterior a hoy.',
            'valor_prestado.required' => 'El valor prestado es obligatorio.',
            'valor_prestado.numeric' => 'El valor prestado debe ser un número.',
            'cuotas.required' => 'El número de cuotas es obligatorio.',
            'cuotas.integer' => 'El número de cuotas debe ser un número entero.',
            'cuotas.min' => 'El número de cuotas debe ser al menos 1.',
            'cuotas.max' => 'El número de cuotas no puede ser mayor a 5.',
            'porcentaje.required' => 'El porcentaje es obligatorio.',
            'porcentaje.integer' => 'El porcentaje debe ser un número entero.',
            'porcentaje.min' => 'Porcentaje no valido.',
            'porcentaje.max' => 'Porcentaje no valido.',
            'producto_id.required' => 'El producto es obligatorio.',
            'producto_id.exists' => 'El producto seleccionado no existe.',
            'cliente_id.required' => 'El cliente es obligatorio.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
        ];
    }
}
