<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:280',
            'post_id' => 'required|integer|exists:posts,id',
            'user_id' => 'required|integer|exists:users,id',
            'parent_id' => 'integer|exists:comments,id'
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
            'body.required' => 'O campo "comentário" é obrigatório.',
            'body.string' => 'O campo "comentário" deve conter texto válido.',
            'body.max' => 'O campo "comentário" não pode ter mais de 280 caracteres.',
            'post_id.required' => 'O campo "post" é obrigatório.',
            'post_id.integer' => 'O campo "post" deve ser um número inteiro.',
            'post_id.exists' => 'O post informado não existe.',
            'user_id.required' => 'O campo "usuário" é obrigatório.',
            'user_id.integer' => 'O campo "usuário" deve ser um número inteiro.',
            'user_id.exists' => 'O usuário informado não existe.',
            'parent_id.integer' => 'O campo "parent_id" deve ser um número inteiro.',
            'parent_id.exists' => 'O comentário informado como "parent_id" não existe.'
        ];
    }
}
