<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OcorrenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'aluno_id' => [
                'required',
                Rule::exists('alunos', 'id')
                    ->where(fn ($query) => $query->where('ativo', true)->whereNotNull('professor_id')),
            ],
            'tipo'        => 'required|in:entrada_atrasada,saida_antecipada',
            'motivo'      => 'required|string|max:1000',
            'observacao'  => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'aluno_id.required' => 'Selecione o aluno.',
            'aluno_id.exists'   => 'Aluno nao encontrado ou sem professor responsavel vinculado.',
            'tipo.required'     => 'Selecione o tipo de ocorrencia.',
            'tipo.in'           => 'Tipo invalido.',
            'motivo.required'   => 'Informe o motivo.',
        ];
    }
}
