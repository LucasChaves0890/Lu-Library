<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowFormRequest extends FormRequest
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
            'following_user_id' => 'required|integer|exists:users,id',
            'followed_user_id' => 'required|integer|exists:users,id'
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
            'following_user_id.required' => 'O campo "usuário que está seguindo" é obrigatório.',
            'following_user_id.integer' => 'O campo "usuário que está seguindo" deve ser um número inteiro.',
            'following_user_id.exists' => 'O "usuário que está seguindo" não existe.',
            'followed_user_id.required' => 'O campo "usuário que está sendo seguido" é obrigatório.',
            'followed_user_id.integer' => 'O campo "usuário que está sendo seguido" deve ser um número inteiro.',
            'followed_user_id.exists' => 'O "usuário que está sendo seguido" não existe.'
        ];
    }
}
