<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpruntRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exemplaires'   => ['required', 'array', 'min:1'],
            'exemplaires.*' => ['exists:exemplaires,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'exemplaires.required' => 'Veuillez sélectionner au moins un exemplaire.',
            'exemplaires.*.exists' => 'Un des exemplaires sélectionnés est invalide.',
        ];
    }
}
