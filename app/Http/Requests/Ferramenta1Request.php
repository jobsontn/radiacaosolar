<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Ferramenta1Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'latitude' => 'required|numeric|between:-34,6',
            'longitude' => 'required|numeric|between:-74,-33',
            'inclinacao' => 'required|numeric|between:0,90',
            'orientacao' => 'required|numeric|between:0,360'
        ];
    }
    public function messages()
    {
        return [
            '*.required' => 'Este campo é obrigatório para o calculo',
            'latitude.between' => 'A latitude deve estar entre -34° e 6°',
            'longitude.between' => 'A longitude deve estar entre -74° e -33°',
            'inclinacao.between' => 'A inclinação deve estar entre 0° e 90°',
            'orientacao.between' => 'A inclinação deve estar entre 0° e 360°'
        ];
    }
    
}
