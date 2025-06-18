<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\FirebaseStorageService;

class CreateCampaignRequest extends ApiRequest
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
            'title' => 'required|string|min:10|max:60',
            'description' => 'required|string|min:100|max:1000',
            'category' => 'required|integer',
            'stages' => 'array',
            'stages.*.title' => 'required|string|min:10|max:60',
            'stages.*.description' => 'required|string|min:100|max:500',
            'stages.*.deadline' => 'required|date|after:today',
            'stages.*.type' => 'required|string|in:money,goods,services',
            'stages.*.money_goal' => 'required_if:stages.*.type,money|numeric|gt:0',
            'stages.*.goods_goal' => 'required_if:stages.*.type,goods|integer|gt:0',
            'stages.*.services_goal' => 'required_if:stages.*.type,services|integer|gt:0',
            'stages.*.needed_goods' => 'required_if:stages.*.type,goods|string|min:3|max:200',
            'stages.*.needed_services' => 'required_if:stages.*.type,services|string|min:3|max:200',
            'stages.*.address' => 'required_if:stages.*.type,services|string|min:50|max:200',


            'images' => 'required|array|min:1|max:3',
            'images.*' =>'required|file|mimes:jpg,jpeg,png|max:51200', // max 50 MB

            'video'=> 'file|mimes:mp4,mov,avi,wmv|max:51200', // max 50 MB
            
             'documents' => 'required|array|min:1|max:5',
            'documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,mp4,mov,avi,wmv|max:51200', // max 50 MB

   

   
]
        
    ;
    }

    protected function prepareForValidation()
{
    $this->merge([
        'stages' => is_string($this->stages) ? json_decode($this->stages, true) : $this->stages,
    ]);
}

    public function messages()
    {
        return [
        'title.required' => 'El título es obligatorio.',
        'title.min' => 'El título debe tener al menos 10 caracteres.',
        'title.max' => 'Haz alcanzado el límite de 60 caracteres.',
        
        'description.required' => 'La descripción es obligatoria.',
        'description.min' => 'La descripción debe tener al menos 100 caracteres.',
        'description.max' => 'Haz alcanzado el límite de 1000 caracteres.',

        'category.required' => 'La categoría es obligatoria.',
        'category.integer' => 'La categoría debe ser un número entero entre 1 y 4.',

        'images.required' => 'Debes subir al menos una imagen.',
        'images.*.mimes' => 'El tipo de archivo que intentas cargar no es valido.',
        'images.*.max' => 'Tu imagen no debe exceder los 50 MB.',
        'images.max' => 'Solo se pueden subir 3 imagenes.',
        'images.min' => 'Debes subir al menos una imagen.',
        'images.*.file' => 'Ocurrió un error, vuelve a cargar tu archivo.',

        'video.file' => 'Ocurrió un error, vuelve a cargar tu video.',
        'video.mimes' => 'El tipo de archivo que intentas cargar no es válido.',
        'video.max' => 'Solo se puedes subir 1 video.',
        'video.max' => 'No puedes subir mas archivos. El peso es de 50 MB.',
        'video.file' => 'Ocurrio un error, vuelve a cargar tu archivo.',


         'documents.required' => 'Debes subir al menos un archivo.',
        'documents.array' => 'El formato de los archivos es incorrecto.',
        'documents.min' => 'Debes subir al menos un archivo.',
        'documents.max' => 'Solo se puede subir un video.',
        'documents.*.required' => 'Debes subir un archivo.',
        'documents.*.file' => 'Ocurrió un error, vuelve a cargar tu archivo.',
        'documents.*.mimes' => 'El tipo de archivo que intentas cargar no es válido.',
        'documents.*.max' => 'Has llegado al limite de archivos permitidos.',
        'documents.*.max' => 'No puedes subir mas archivos. El peso total permitido es de 50 MB.',



        'stages.*.title.required' => 'El título es obligatorio.',
        'stages.*.title.min' => 'El título debe tener entre 3 y 60 caracteres.',
        'stages.*.title.max' => 'El título debe tener entre 3 y 60 caracteres.',

      
        'stages.*.description.required' => 'La descripción es obligatoria.',
        'stages.*.description.min' => 'La descripción debe tener entre 100 y 500 caracteres.',
        'stages.*.description.max' => 'La descripción debe tener entre 100 y 500 caracteres.',

        
        'stages.*.deadline.required' => 'La fecha límite es obligatoria.',
        'stages.*.deadline.after' => 'La fecha límite no puede ser una fecha anterior o igual a la actual.',
        'stages.*.deadline.date' => 'La fecha límite debe ser una fecha válida.',

       
        'stages.*.amount.required_if' => 'La cantidad necesitada es obligatoria.',
        'stages.*.amount.numeric' => 'La cantidad debe ser un número mayor a cero.',
        'stages.*.amount.gt' => 'La cantidad debe ser un número mayor a cero.',

        'stages.*.needed_goods.required_if' => 'Los bienes necesarios son obligatorios.',
        'stages.*.needed_goods.string' => 'Los bienes necesarios deben ser texto.',
        'stages.*.needed_goods.min' => 'El campo de bienes necesarios deben tener entre 10 y 200 caracteres.',
        'stages.*.needed_goods.max' => 'El campo de bienes necesarios deben tener entre 10 y 200 caracteres.',

        'stages.*.address.required_if' => 'La dirección es obligatoria.',
        'stages.*.address.string' => 'La dirección debe ser texto.',
        'stages.*.address.min' => 'La dirección debe tener entre 50 y 200 caracteres.',
        'stages.*.address.max' => 'La dirección debe tener entre 50 y 200 caracteres.',

        'stages.*.needy_person.required_if' => 'La cantidad de personal es obligatoria.',
        'stages.*.needy_person.integer' => 'La cantidad de personas debe ser un número entero mayor a cero.',
        'stages.*.needy_person.gt' => 'La cantidad de personas debe ser un número mayor a cero.',

        'stages.*.type_of_service.required_if' => 'El tipo de servicio es obligatorio.',
        'stages.*.type_of_service.string' => 'El tipo de servicio debe ser texto.',
        'stages.*.type_of_service.min' => 'El tipo de servicio debe tener entre 10 y 200 caracteres.',
        'stages.*.type_of_service.max' => 'El tipo de servicio debe tener entre 10 y 200 caracteres.',


       


         
        ];
    }
}
