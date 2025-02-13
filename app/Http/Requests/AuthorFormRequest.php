<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthorFormRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:40',
            'sex' => ['required', 'string',Rule::in('feminino', 'masculino')],
            'description' => 'nullable|string',
            'nacionality'=> 'required|string',
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
            'name.required' => 'O campo "nome" é obrigatório.',
            'name.string' => 'O campo "nome" deve conter apenas caracteres válidos.',
            'name.min' => 'O campo "nome" deve ter pelo menos 3 caracteres.',
            'name.max' => 'O campo "nome" não pode ter mais de 40 caracteres.',
            'sex.required' => 'O campo "sexo" é obrigatório.',
            'sex.string' => 'O campo "sexo" deve conter apenas caracteres válidos.',
            'sex.in' => 'O campo "sexo" deve ser "feminino" ou "masculino".',
            'description.string' => 'O campo "descrição" deve conter apenas caracteres válidos.',
            'nacionality.required' => 'O campo "nacionalidade" é obrigatório.',
            'nacionality.string' => 'O campo "nacionalidade" deve conter apenas caracteres válidos.',
        ];
    }
}
