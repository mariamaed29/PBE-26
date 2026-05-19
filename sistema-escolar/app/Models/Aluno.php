<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aluno extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'rm',
        'turma',
        'curso',
        'responsavel',
        'telefone_responsavel',
        'email_responsavel',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // Relacionamentos
    public function ocorrencias()
    {
        return $this->hasMany(Ocorrencia::class);
    }

    // Atrasos do aluno
    public function atrasos()
    {
        return $this->ocorrencias()->where('tipo', 'entrada_atrasada');
    }

    // Saídas antecipadas
    public function saidasAntecipadas()
    {
        return $this->ocorrencias()->where('tipo', 'saida_antecipada');
    }

    // Scope para busca
    public function scopeBusca($query, $termo)
    {
        return $query->where('nome', 'like', "%{$termo}%")
                     ->orWhere('rm', 'like', "%{$termo}%")
                     ->orWhere('turma', 'like', "%{$termo}%");
    }
}

