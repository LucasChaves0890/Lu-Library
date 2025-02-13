<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRatingFormRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id',
            'rating' => 'required|numeric|min:0|max:5'
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
            'user_id.required' => 'O campo "usuário" é obrigatório.',
            'user_id.integer' => 'O campo "usuário" deve ser um número inteiro.',
            'user_id.exists' => 'O usuário informado não existe.',
            'book_id.required' => 'O campo "livro" é obrigatório.',
            'book_id.integer' => 'O campo "livro" deve ser um número inteiro.',
            'book_id.exists' => 'O livro informado não existe.',
            'rating.required' => 'O campo "avaliação" é obrigatório.',
            'rating.numeric' => 'O campo "avaliação" deve ser um valor numérico.',
            'rating.min' => 'A avaliação deve ser no mínimo 0.',
            'rating.max' => 'A avaliação deve ser no máximo 5.',
        ];
    }
}
