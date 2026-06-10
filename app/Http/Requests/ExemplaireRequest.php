<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExemplaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'livre_id'        => ['required', 'exists:livres,id'],
            'statut_id'       => ['required', 'exists:statuts,id'],
            'mise_en_service' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'livre_id.required'        => 'Veuillez sélectionner un livre.',
            'livre_id.exists'          => 'Le livre sélectionné n\'existe pas.',
            'statut_id.required'       => 'Veuillez sélectionner un statut.',
            'mise_en_service.required' => 'La date de mise en service est obligatoire.',
            'mise_en_service.before_or_equal' => 'La date de mise en service ne peut pas être dans le futur.',
        ];
    }
}
