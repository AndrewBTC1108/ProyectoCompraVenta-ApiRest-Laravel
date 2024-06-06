<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClienteRequest extends FormRequest
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
            return[
                'cedula' => ['required', 'unique:clientes,cedula', 'max_digits:10'],
                'nombre' => ['required'],
                'apellido' => ['required'],
                'telefono' => ['required', 'max_digits:10'],
                'direccion_residencia' => ['required']
            ];
        }else {
            return [
                'cedula' => ['sometimes', 'required', 'unique:clientes,cedula', 'max_digits:10'],
                'nombre' => ['sometimes', 'required'],
                'apellido' => ['sometimes', 'required'],
                'telefono' => ['sometimes', 'required', 'max_digits:10'],
                'direccion_residencia' => ['sometimes', 'required']
            ];
        }
    }

    public function messages()
    {
        return [
            'cedula.required' => 'La cedula es obligatoria',
            'cedula.unique' => 'Ya existe un usuario con ese numero de cedula',
            'cedula.max_digits' => 'La cédula debe tener exactamente 10 caracteres y deben ser numeros',
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'telefono.required' => 'El Telefono es requerido',
            'telefono.max_digits' => 'El telefono debe tener exactamente 10 caracteres y deben ser numeros',
            'direccion_residencia.required' => 'La direccion del cliente es obligatoria'
        ];
    }
}
