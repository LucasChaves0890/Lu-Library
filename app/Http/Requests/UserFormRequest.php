<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
        $rules = []; // Inicialização da variável

        if ($this->isMethod('put')) {
            $rules['username'] = 'string|max:280';
            $rules['description'] = 'string|nullable';
        }

        if ($this->isMethod('post')) {
            $rules['username'] = 'required|string|max:255|min:2';
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|confirmed';
            $rules['sex'] = 'required|string|in:feminino,masculino'; // Corrigido
            $rules['description'] = 'nullable|string';
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
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
