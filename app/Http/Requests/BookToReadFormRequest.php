<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookToReadFormRequest extends FormRequest
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
            'pages_read' => 'integer'
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
            'pages_read.integer' => 'O campo "páginas lidas" deve ser um número inteiro.'
        ];
    }
}
