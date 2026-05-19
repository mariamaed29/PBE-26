<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OcorrenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAqv();
    }

    public function rules(): array
    {
        return [
            'aluno_id'    => 'required|exists:alunos,id',
            'tipo'        => 'required|in:entrada_atrasada,saida_antecipada',
            'motivo'      => 'required|string|max:1000',
            'observacao'  => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'aluno_id.required' => 'Selecione o aluno.',
            'aluno_id.exists'   => 'Aluno não encontrado.',
            'tipo.required'     => 'Selecione o tipo de ocorrência.',
            'tipo.in'           => 'Tipo inválido.',
            'motivo.required'   => 'Informe o motivo.',
        ];
    }
}


