<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'professor_id'          => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'professor')),
            ],
            'responsavel'           => 'required|string|max:255',
            'telefone_responsavel'  => 'required|string|max:20',
            'email_responsavel'     => 'nullable|email|max:255',
            'ativo'                 => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'                 => 'O nome e obrigatorio.',
            'rm.required'                   => 'O RM e obrigatorio.',
            'rm.unique'                     => 'Este RM ja esta cadastrado.',
            'turma.required'                => 'A turma e obrigatoria.',
            'curso.required'                => 'O curso e obrigatorio.',
            'professor_id.required'         => 'Selecione o professor responsavel.',
            'professor_id.exists'           => 'Selecione um professor valido.',
            'responsavel.required'          => 'O nome do responsavel e obrigatorio.',
            'telefone_responsavel.required' => 'O telefone do responsavel e obrigatorio.',
        ];
    }
}
