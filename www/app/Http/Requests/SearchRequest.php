<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Todos pueden hacer búsquedas
    }

    public function rules(): array
    {
        return [
            'query' => 'required|string|max:50|min:2'
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'query' => trim($this->input('query', ''))
        ]);
    }

    public function messages(): array
    {
        return [
            'query.required' => 'El campo búsqueda es obligatorio',
            'query.max' => 'La búsqueda no puede tener más de 50 caracteres',
            'query.min' => 'La búsqueda debe tener al menos 2 caracteres'
        ];
    }
}