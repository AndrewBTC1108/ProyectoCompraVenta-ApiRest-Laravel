<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
        $method = $this->method();
        if($method == 'PUT'){
            return [
                'nombre' => ['required'],
                'tipo' => ['required', 'in:joya,vehiculo,electrodomestico'],
                'observaciones' => ['required'],
                'cliente_id' => ['required', 'exists:clientes,id']
            ];
        } else {
            return [
                'nombre' => ['sometimes', 'required'],
                'tipo' => ['sometimes', 'required', 'in:joya,vehiculo,electrodomestico'],
                'observaciones' => ['sometimes', 'required'],
                'cliente_id' => ['sometimes', 'required', 'exists:clientes,id']
            ];
        }
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'tipo.required' => 'El tipo de producto es obligatorio',
            'tipo.in' => 'Tipo no permitido',
            'observaciones.required' => 'Las observaciones son requeridas',
            'cliente_id.required' => 'El cliente es obligatorio.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
        ];
    }
}
