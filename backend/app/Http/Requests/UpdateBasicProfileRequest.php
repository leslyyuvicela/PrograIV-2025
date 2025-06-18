<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;



class UpdateBasicProfileRequest extends ApiRequest
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
            'name' => 'sometimes|required|string|max:255',
            'surname' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:8'
           
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El nombre es obligatorio.',
            'surname.required' => 'El apellido es obligatorio.',
             'phone.required' => 'El teléfono es obligatorio.'
        ];
    }
}