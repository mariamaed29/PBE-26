<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $alunoId = $this->route('aluno')?->id;

        return [
            'nome'                  => 'required|string|max:255',
            'rm'                    => 'required|string|max:50|unique:alunos,rm,' . $alunoId,
            'turma'                 => 'required|string|max:50',
            'curso'                 => 'required|string|max:100',
            'responsavel'           => 'required|string|max:255',
            'telefone_responsavel'  => 'required|string|max:20',
            'email_responsavel'     => 'nullable|email|max:255',
            'ativo'                 => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'                 => 'O nome é obrigatório.',
            'rm.required'                   => 'O RM é obrigatório.',
            'rm.unique'                     => 'Este RM já está cadastrado.',
            'turma.required'                => 'A turma é obrigatória.',
            'curso.required'                => 'O curso é obrigatório.',
            'responsavel.required'          => 'O nome do responsável é obrigatório.',
            'telefone_responsavel.required' => 'O telefone do responsável é obrigatório.',
        ];
    }
}