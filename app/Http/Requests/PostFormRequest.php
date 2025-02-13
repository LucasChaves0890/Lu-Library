<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
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
        $rules = [];

        if ($this->isMethod('put')) {
            $rules['body'] = 'string|max:280';
        }
        

        if ($this->isMethod('post')) {
            $rules['user_id'] = 'required|integer|exists:users,id';
            $rules['book_id'] = 'required|integer|exists:books,id';
            $rules['body'] = 'required|string';
        }

        return $rules;
    }

    public function messages():array
    {
        return [
            'user_id.required' => 'O id do usuário é obrigatorio.',
            'user_id.integer' => 'O id precisa ser um número.',
            'user_id.exists' => 'Usuário não existe.' ,
            'book_id.required' => 'O livro é obrigatorio.',
            'book_id.integer' => 'O id precisa ser um número.',
            'book_id.exists' => 'Livro não existe.',
            'body.required' => 'O post precisa de um comentário.',
            'body.string' => 'O comentário precisa ser um texto válido.'
        ];
    }
    
}
