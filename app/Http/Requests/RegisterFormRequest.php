<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterFormRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'max:255',
                'min:2',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
            ],
            'sex' => [
                'required',
                'string',
                Rule::in(['feminino', 'masculino']),
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'O campo nome de usuário é obrigatório.',
            'username.string' => 'O nome de usuário deve ser uma string.',
            'username.max' => 'O nome de usuário não pode ter mais de 255 caracteres.',
            'username.min' => 'O nome de usuário deve ter pelo menos 2 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço de e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
            'email.unique' => 'O e-mail já está em uso.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'A senha deve ser uma string.',
            'password.confirmed' => 'As senhas não coincidem.',
            'sex.required' => 'O campo sexo é obrigatório.',
            'sex.string' => 'O sexo deve ser uma string.',
            'sex.in' => 'O sexo deve ser feminino ou masculino.',
            'description.nullable' => 'A descrição pode ser nula.',
            'description.string' => 'A descrição deve ser uma string.',
        ];
    }
}
