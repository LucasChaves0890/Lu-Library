<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookFormRequest extends FormRequest
{
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
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string',
            'gender' => 'required|string|max:255',
            'price' => 'required|numeric',
            'author_id' => 'required|exists:authors,id',
            'number_of_pages' => 'required|integer|min:1',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O campo "título" é obrigatório.',
            'title.string' => 'O campo "título" deve conter apenas texto.',
            'title.min' => 'O campo "título" deve ter pelo menos 3 caracteres.',
            'title.max' => 'O campo "título" não pode ter mais do que 255 caracteres.',
            'description.required' => 'O campo "descrição" é obrigatório.',
            'description.string' => 'O campo "descrição" deve conter apenas texto.',
            'gender.required' => 'O campo "gênero" é obrigatório.',
            'gender.string' => 'O campo "gênero" deve conter apenas texto.',
            'gender.max' => 'O campo "gênero" não pode ter mais do que 255 caracteres.',
            'price.required' => 'O campo "preço" é obrigatório.',
            'price.numeric' => 'O campo "preço" deve ser um valor numérico.',
            'author_id.required' => 'O campo "autor" é obrigatório.',
            'author_id.exists' => 'O autor informado não existe.',
            'number_of_pages.required' => 'O campo "número de páginas" é obrigatório.',
            'number_of_pages.integer' => 'O campo "número de páginas" deve ser um número inteiro.',
            'number_of_pages.min' => 'O campo "número de páginas" deve ser no mínimo 1.',
        ];
    }
}
