<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
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
            'username.required' => 'O campo "nome de usuário" é obrigatório.',
            'username.string' => 'O campo "nome de usuário" deve ser uma string válida.',
            'username.max' => 'O campo "nome de usuário" não pode exceder 255 caracteres.',
            
            'email.required' => 'O campo "e-mail" é obrigatório.',
            'email.string' => 'O campo "e-mail" deve ser uma string válida.',
            'email.lowercase' => 'O campo "e-mail" deve ser em letras minúsculas.',
            'email.email' => 'O campo "e-mail" deve ser um e-mail válido.',
            'email.max' => 'O campo "e-mail" não pode exceder 255 caracteres.',
            'email.unique' => 'O e-mail informado já está em uso. Por favor, escolha outro.',
        ];
    }
}
